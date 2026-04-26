// SPDX-License-Identifier: MIT
pragma solidity ^0.8.19;

/**
 * @title AgroSphereEscrow
 * @dev Smart contract for secure payment escrow between buyers and farmers
 * @author AgroSphere Team
 */
contract AgroSphereEscrow {
    
    enum EscrowStatus {
        Pending,        // Funds locked, waiting for delivery
        Delivered,      // Delivery confirmed by buyer
        Released,       // Payment released to farmer
        Refunded,       // Refunded to buyer (dispute)
        Cancelled       // Order cancelled before delivery
    }
    
    struct Escrow {
        uint256 orderId;
        address buyer;
        address farmer;
        uint256 amount;
        uint256 platformFee;
        EscrowStatus status;
        uint256 createdAt;
        uint256 deliveredAt;
        uint256 releasedAt;
        string productName;
        uint256 productId;
        uint256 quantity;
        string deliveryLocation;
    }
    
    // Mapping from orderId to escrow
    mapping(uint256 => Escrow) public escrows;
    
    // Platform fee percentage (in basis points, e.g., 250 = 2.5%)
    uint256 public platformFeeBps = 250;
    uint256 public constant MAX_FEE_BPS = 1000; // Max 10%
    
    // Platform treasury address
    address public treasury;
    
    // Contract owner
    address public owner;
    
    // Authorized order creators
    mapping(address => bool) public authorizedCreators;
    
    // Total escrowed amount
    uint256 public totalEscrowed;
    uint256 public totalReleased;
    uint256 public totalRefunded;
    
    // Order IDs array for tracking
    uint256[] public allOrderIds;
    
    // Events
    event EscrowCreated(
        uint256 indexed orderId,
        address indexed buyer,
        address indexed farmer,
        uint256 amount
    );
    
    event DeliveryConfirmed(
        uint256 indexed orderId,
        address indexed buyer,
        uint256 timestamp
    );
    
    event PaymentReleased(
        uint256 indexed orderId,
        address indexed farmer,
        uint256 amount,
        uint256 fee
    );
    
    event PaymentRefunded(
        uint256 indexed orderId,
        address indexed buyer,
        uint256 amount
    );
    
    event OrderCancelled(
        uint256 indexed orderId,
        address indexed by,
        uint256 timestamp
    );
    
    // Modifiers
    modifier onlyOwner() {
        require(msg.sender == owner, "Only owner");
        _;
    }
    
    modifier onlyAuthorized() {
        require(
            authorizedCreators[msg.sender] || msg.sender == owner,
            "Not authorized"
        );
        _;
    }
    
    modifier validEscrow(uint256 _orderId) {
        require(escrows[_orderId].orderId != 0, "Escrow not found");
        _;
    }
    
    // Constructor
    constructor(address _treasury) {
        owner = msg.sender;
        treasury = _treasury;
        authorizedCreators[msg.sender] = true;
    }
    
    /**
     * @dev Create escrow for an order (called by backend)
     */
    function createEscrow(
        uint256 _orderId,
        address _buyer,
        address _farmer,
        string memory _productName,
        uint256 _productId,
        uint256 _quantity,
        string memory _deliveryLocation
    ) external payable onlyAuthorized returns (bool) {
        
        require(_orderId > 0, "Invalid order ID");
        require(_buyer != address(0) && _farmer != address(0), "Invalid addresses");
        require(msg.value > 0, "Payment required");
        require(escrows[_orderId].orderId == 0, "Escrow already exists");
        
        uint256 platformFee = (msg.value * platformFeeBps) / 10000;
        uint256 farmerAmount = msg.value - platformFee;
        
        Escrow memory escrow = Escrow({
            orderId: _orderId,
            buyer: _buyer,
            farmer: _farmer,
            amount: farmerAmount,
            platformFee: platformFee,
            status: EscrowStatus.Pending,
            createdAt: block.timestamp,
            deliveredAt: 0,
            releasedAt: 0,
            productName: _productName,
            productId: _productId,
            quantity: _quantity,
            deliveryLocation: _deliveryLocation
        });
        
        escrows[_orderId] = escrow;
        allOrderIds.push(_orderId);
        totalEscrowed += msg.value;
        
        emit EscrowCreated(_orderId, _buyer, _farmer, msg.value);
        
        return true;
    }
    
    /**
     * @dev Confirm delivery by buyer
     */
    function confirmDelivery(uint256 _orderId) 
        external 
        validEscrow(_orderId) 
    {
        Escrow storage escrow = escrows[_orderId];
        
        require(msg.sender == escrow.buyer, "Only buyer can confirm");
        require(escrow.status == EscrowStatus.Pending, "Invalid status");
        
        escrow.status = EscrowStatus.Delivered;
        escrow.deliveredAt = block.timestamp;
        
        emit DeliveryConfirmed(_orderId, msg.sender, block.timestamp);
    }
    
    /**
     * @dev Release payment to farmer after delivery
     */
    function releasePayment(uint256 _orderId) 
        external 
        validEscrow(_orderId) 
    {
        Escrow storage escrow = escrows[_orderId];
        
        require(
            msg.sender == escrow.buyer || msg.sender == owner,
            "Not authorized"
        );
        require(escrow.status == EscrowStatus.Delivered, "Not delivered yet");
        
        escrow.status = EscrowStatus.Released;
        escrow.releasedAt = block.timestamp;
        
        // Transfer to farmer
        (bool farmerSuccess, ) = payable(escrow.farmer).call{value: escrow.amount}("");
        require(farmerSuccess, "Farmer transfer failed");
        
        // Transfer fee to treasury
        if (escrow.platformFee > 0) {
            (bool feeSuccess, ) = payable(treasury).call{value: escrow.platformFee}("");
            require(feeSuccess, "Fee transfer failed");
        }
        
        totalReleased += escrow.amount;
        
        emit PaymentReleased(_orderId, escrow.farmer, escrow.amount, escrow.platformFee);
    }
    
    /**
     * @dev Refund buyer (in case of dispute/failed delivery)
     */
    function refundBuyer(uint256 _orderId) 
        external 
        onlyOwner
        validEscrow(_orderId) 
    {
        Escrow storage escrow = escrows[_orderId];
        
        require(
            escrow.status == EscrowStatus.Pending || 
            escrow.status == EscrowStatus.Delivered,
            "Cannot refund"
        );
        
        uint256 refundAmount = escrow.amount + escrow.platformFee;
        
        escrow.status = EscrowStatus.Refunded;
        
        (bool success, ) = payable(escrow.buyer).call{value: refundAmount}("");
        require(success, "Refund failed");
        
        totalRefunded += refundAmount;
        
        emit PaymentRefunded(_orderId, escrow.buyer, refundAmount);
    }
    
    /**
     * @dev Cancel order before delivery (mutual agreement)
     */
    function cancelOrder(uint256 _orderId) 
        external 
        validEscrow(_orderId) 
    {
        Escrow storage escrow = escrows[_orderId];
        
        require(
            msg.sender == escrow.buyer || msg.sender == escrow.farmer || msg.sender == owner,
            "Not authorized"
        );
        require(escrow.status == EscrowStatus.Pending, "Can only cancel pending orders");
        
        uint256 refundAmount = escrow.amount + escrow.platformFee;
        
        escrow.status = EscrowStatus.Cancelled;
        
        (bool success, ) = payable(escrow.buyer).call{value: refundAmount}("");
        require(success, "Refund failed");
        
        emit OrderCancelled(_orderId, msg.sender, block.timestamp);
    }
    
    /**
     * @dev Get escrow details
     */
    function getEscrow(uint256 _orderId) 
        external 
        view 
        returns (Escrow memory) 
    {
        return escrows[_orderId];
    }
    
    /**
     * @dev Get buyer's active orders
     */
    function getBuyerOrders(address _buyer) 
        external 
        view 
        returns (uint256[] memory) 
    {
        uint256 count = 0;
        for (uint256 i = 0; i < allOrderIds.length; i++) {
            if (escrows[allOrderIds[i]].buyer == _buyer) {
                count++;
            }
        }
        
        uint256[] memory orders = new uint256[](count);
        uint256 index = 0;
        for (uint256 i = 0; i < allOrderIds.length; i++) {
            if (escrows[allOrderIds[i]].buyer == _buyer) {
                orders[index] = allOrderIds[i];
                index++;
            }
        }
        
        return orders;
    }
    
    /**
     * @dev Get farmer's active orders
     */
    function getFarmerOrders(address _farmer) 
        external 
        view 
        returns (uint256[] memory) 
    {
        uint256 count = 0;
        for (uint256 i = 0; i < allOrderIds.length; i++) {
            if (escrows[allOrderIds[i]].farmer == _farmer) {
                count++;
            }
        }
        
        uint256[] memory orders = new uint256[](count);
        uint256 index = 0;
        for (uint256 i = 0; i < allOrderIds.length; i++) {
            if (escrows[allOrderIds[i]].farmer == _farmer) {
                orders[index] = allOrderIds[i];
                index++;
            }
        }
        
        return orders;
    }
    
    /**
     * @dev Update platform fee
     */
    function updatePlatformFee(uint256 _newFeeBps) external onlyOwner {
        require(_newFeeBps <= MAX_FEE_BPS, "Fee too high");
        platformFeeBps = _newFeeBps;
    }
    
    /**
     * @dev Update treasury address
     */
    function updateTreasury(address _newTreasury) external onlyOwner {
        require(_newTreasury != address(0), "Invalid address");
        treasury = _newTreasury;
    }
    
    /**
     * @dev Add authorized creator
     */
    function addAuthorizedCreator(address _creator) external onlyOwner {
        authorizedCreators[_creator] = true;
    }
    
    /**
     * @dev Remove authorized creator
     */
    function removeAuthorizedCreator(address _creator) external onlyOwner {
        authorizedCreators[_creator] = false;
    }
    
    /**
     * @dev Get contract stats
     */
    function getStats() 
        external 
        view 
        returns (
            uint256 totalOrders,
            uint256 totalEscrowedAmount,
            uint256 totalReleasedAmount,
            uint256 totalRefundedAmount,
            uint256 currentBalance
        ) 
    {
        return (
            allOrderIds.length,
            totalEscrowed,
            totalReleased,
            totalRefunded,
            address(this).balance
        );
    }
    
    /**
     * @dev Emergency withdrawal (only owner, for stuck funds)
     */
    function emergencyWithdraw() external onlyOwner {
        uint256 balance = address(this).balance;
        require(balance > 0, "No funds");
        
        (bool success, ) = payable(owner).call{value: balance}("");
        require(success, "Withdrawal failed");
    }
    
    // Receive function
    receive() external payable {
        revert("Use createEscrow function");
    }
}
