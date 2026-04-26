// SPDX-License-Identifier: MIT
pragma solidity ^0.8.19;

/**
 * @title AgroSphereOrigin
 * @dev Smart contract for verifying farm product origin on-chain
 * @author AgroSphere Team
 */
contract AgroSphereOrigin {
    
    struct ProductVerification {
        uint256 productId;
        address farmer;
        string farmerName;
        string region;
        string batchId;
        uint256 harvestDate;
        string productName;
        string category;
        bool isOrganic;
        bool isVerified;
        uint256 verifiedAt;
        string ipfsHash; // Optional: For storing additional documents
    }
    
    // Mapping from productId to verification record
    mapping(uint256 => ProductVerification) public verifications;
    
    // Mapping from batchId to productId for quick lookup
    mapping(string => uint256) public batchToProduct;
    
    // Array to track all verified products
    uint256[] public verifiedProductIds;
    
    // Authorized verifiers (admin addresses)
    mapping(address => bool) public authorizedVerifiers;
    
    // Contract owner
    address public owner;
    
    // Events
    event ProductVerified(
        uint256 indexed productId,
        address indexed farmer,
        string batchId,
        uint256 timestamp
    );
    
    event VerificationRevoked(
        uint256 indexed productId,
        address indexed revokedBy,
        uint256 timestamp
    );
    
    event VerifierAdded(address indexed verifier);
    event VerifierRemoved(address indexed verifier);
    
    // Modifiers
    modifier onlyOwner() {
        require(msg.sender == owner, "Only owner can call this function");
        _;
    }
    
    modifier onlyAuthorizedVerifier() {
        require(
            authorizedVerifiers[msg.sender] || msg.sender == owner,
            "Not authorized to verify products"
        );
        _;
    }
    
    // Constructor
    constructor() {
        owner = msg.sender;
        authorizedVerifiers[msg.sender] = true;
    }
    
    /**
     * @dev Verify a farm product and store on-chain
     */
    function verifyProduct(
        uint256 _productId,
        address _farmer,
        string memory _farmerName,
        string memory _region,
        string memory _batchId,
        uint256 _harvestDate,
        string memory _productName,
        string memory _category,
        bool _isOrganic,
        string memory _ipfsHash
    ) external onlyAuthorizedVerifier returns (bool) {
        
        require(_productId > 0, "Invalid product ID");
        require(_farmer != address(0), "Invalid farmer address");
        require(bytes(_batchId).length > 0, "Batch ID required");
        require(verifications[_productId].productId == 0, "Product already verified");
        
        ProductVerification memory verification = ProductVerification({
            productId: _productId,
            farmer: _farmer,
            farmerName: _farmerName,
            region: _region,
            batchId: _batchId,
            harvestDate: _harvestDate,
            productName: _productName,
            category: _category,
            isOrganic: _isOrganic,
            isVerified: true,
            verifiedAt: block.timestamp,
            ipfsHash: _ipfsHash
        });
        
        verifications[_productId] = verification;
        batchToProduct[_batchId] = _productId;
        verifiedProductIds.push(_productId);
        
        emit ProductVerified(_productId, _farmer, _batchId, block.timestamp);
        
        return true;
    }
    
    /**
     * @dev Get product verification details
     */
    function getProductVerification(uint256 _productId) 
        external 
        view 
        returns (ProductVerification memory) 
    {
        return verifications[_productId];
    }
    
    /**
     * @dev Check if product is verified
     */
    function isProductVerified(uint256 _productId) external view returns (bool) {
        return verifications[_productId].isVerified;
    }
    
    /**
     * @dev Get product by batch ID
     */
    function getProductByBatch(string memory _batchId) 
        external 
        view 
        returns (ProductVerification memory) 
    {
        uint256 productId = batchToProduct[_batchId];
        return verifications[productId];
    }
    
    /**
     * @dev Revoke verification (in case of fraud/dispute)
     */
    function revokeVerification(uint256 _productId) 
        external 
        onlyAuthorizedVerifier 
    {
        require(verifications[_productId].isVerified, "Product not verified");
        
        verifications[_productId].isVerified = false;
        
        emit VerificationRevoked(_productId, msg.sender, block.timestamp);
    }
    
    /**
     * @dev Add authorized verifier
     */
    function addVerifier(address _verifier) external onlyOwner {
        require(_verifier != address(0), "Invalid address");
        authorizedVerifiers[_verifier] = true;
        emit VerifierAdded(_verifier);
    }
    
    /**
     * @dev Remove authorized verifier
     */
    function removeVerifier(address _verifier) external onlyOwner {
        authorizedVerifiers[_verifier] = false;
        emit VerifierRemoved(_verifier);
    }
    
    /**
     * @dev Get total verified products count
     */
    function getTotalVerifiedProducts() external view returns (uint256) {
        return verifiedProductIds.length;
    }
    
    /**
     * @dev Get verified products with pagination
     */
    function getVerifiedProducts(uint256 _page, uint256 _perPage) 
        external 
        view 
        returns (ProductVerification[] memory) 
    {
        uint256 start = _page * _perPage;
        require(start < verifiedProductIds.length, "Page out of range");
        
        uint256 end = start + _perPage;
        if (end > verifiedProductIds.length) {
            end = verifiedProductIds.length;
        }
        
        uint256 resultLength = end - start;
        ProductVerification[] memory result = new ProductVerification[](resultLength);
        
        for (uint256 i = 0; i < resultLength; i++) {
            result[i] = verifications[verifiedProductIds[start + i]];
        }
        
        return result;
    }
    
    /**
     * @dev Transfer ownership
     */
    function transferOwnership(address _newOwner) external onlyOwner {
        require(_newOwner != address(0), "Invalid address");
        owner = _newOwner;
    }
}
