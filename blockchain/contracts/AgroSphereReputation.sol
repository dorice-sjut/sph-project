// SPDX-License-Identifier: MIT
pragma solidity ^0.8.19;

/**
 * @title AgroSphereReputation
 * @dev Smart contract for farmer reputation and trust score on-chain
 * @author AgroSphere Team
 */
contract AgroSphereReputation {
    
    struct FarmerProfile {
        address farmerAddress;
        string farmerName;
        uint256 totalTransactions;
        uint256 successfulDeliveries;
        uint256 failedDeliveries;
        uint256 totalVolumeSold;
        uint256 trustScore;
        uint256 registrationDate;
        bool isVerified;
        string region;
        uint256 ratingSum;
        uint256 ratingCount;
        uint256 lastActivity;
    }
    
    struct Review {
        uint256 reviewId;
        address reviewer;
        address farmer;
        uint256 rating;
        string comment;
        uint256 timestamp;
        bool isVerifiedPurchase;
        uint256 orderId;
    }
    
    mapping(address => FarmerProfile) public farmers;
    address[] public registeredFarmers;
    
    mapping(uint256 => Review) public reviews;
    mapping(address => uint256[]) public farmerReviews;
    uint256 public reviewCounter;
    
    mapping(address => bool) public authorizedContracts;
    address public owner;
    
    uint256 public constant EXCELLENT_THRESHOLD = 900;
    uint256 public constant GOOD_THRESHOLD = 750;
    uint256 public constant AVERAGE_THRESHOLD = 500;
    
    event FarmerRegistered(address indexed farmer, string name, string region, uint256 timestamp);
    event ReputationUpdated(address indexed farmer, uint256 newTrustScore, uint256 totalTransactions, uint256 timestamp);
    event ReviewAdded(uint256 indexed reviewId, address indexed farmer, address indexed reviewer, uint256 rating);
    event FarmerVerified(address indexed farmer, address indexed verifier);
    event FarmerUnverified(address indexed farmer, address indexed verifier);
    
    modifier onlyOwner() {
        require(msg.sender == owner, "Only owner");
        _;
    }
    
    modifier onlyAuthorized() {
        require(authorizedContracts[msg.sender] || msg.sender == owner, "Not authorized");
        _;
    }
    
    constructor() {
        owner = msg.sender;
        authorizedContracts[msg.sender] = true;
    }
    
    function registerFarmer(address _farmer, string memory _name, string memory _region) 
        external 
        onlyAuthorized 
        returns (bool) 
    {
        require(_farmer != address(0), "Invalid address");
        require(bytes(farmers[_farmer].farmerName).length == 0, "Already registered");
        
        farmers[_farmer] = FarmerProfile({
            farmerAddress: _farmer,
            farmerName: _name,
            totalTransactions: 0,
            successfulDeliveries: 0,
            failedDeliveries: 0,
            totalVolumeSold: 0,
            trustScore: 500,
            registrationDate: block.timestamp,
            isVerified: false,
            region: _region,
            ratingSum: 0,
            ratingCount: 0,
            lastActivity: block.timestamp
        });
        
        registeredFarmers.push(_farmer);
        emit FarmerRegistered(_farmer, _name, _region, block.timestamp);
        return true;
    }
    
    function recordSuccessfulTransaction(address _farmer, uint256 _volume) external onlyAuthorized {
        require(bytes(farmers[_farmer].farmerName).length > 0, "Farmer not registered");
        
        FarmerProfile storage farmer = farmers[_farmer];
        farmer.totalTransactions++;
        farmer.successfulDeliveries++;
        farmer.totalVolumeSold += _volume;
        farmer.lastActivity = block.timestamp;
        
        _updateTrustScore(_farmer);
        
        emit ReputationUpdated(_farmer, farmer.trustScore, farmer.totalTransactions, block.timestamp);
    }
    
    function recordFailedTransaction(address _farmer) external onlyAuthorized {
        require(bytes(farmers[_farmer].farmerName).length > 0, "Farmer not registered");
        
        FarmerProfile storage farmer = farmers[_farmer];
        farmer.totalTransactions++;
        farmer.failedDeliveries++;
        farmer.lastActivity = block.timestamp;
        
        _updateTrustScore(_farmer);
        
        emit ReputationUpdated(_farmer, farmer.trustScore, farmer.totalTransactions, block.timestamp);
    }
    
    function _updateTrustScore(address _farmer) internal {
        FarmerProfile storage farmer = farmers[_farmer];
        
        if (farmer.totalTransactions == 0) {
            farmer.trustScore = 500;
            return;
        }
        
        uint256 successRate = (farmer.successfulDeliveries * 1000) / farmer.totalTransactions;
        uint256 baseScore = (successRate * 700) / 1000;
        
        uint256 ratingScore = 0;
        if (farmer.ratingCount > 0) {
            uint256 avgRating = farmer.ratingSum / farmer.ratingCount;
            ratingScore = ((avgRating - 1) * 300) / 4;
        } else {
            ratingScore = 150;
        }
        
        farmer.trustScore = baseScore + ratingScore;
        if (farmer.trustScore > 1000) farmer.trustScore = 1000;
    }
    
    function addReview(address _farmer, uint256 _rating, string memory _comment, 
                       bool _isVerifiedPurchase, uint256 _orderId) 
        external 
        returns (uint256) 
    {
        require(bytes(farmers[_farmer].farmerName).length > 0, "Farmer not registered");
        require(_rating >= 1 && _rating <= 5, "Rating must be 1-5");
        require(msg.sender != _farmer, "Cannot review yourself");
        
        reviewCounter++;
        
        reviews[reviewCounter] = Review({
            reviewId: reviewCounter,
            reviewer: msg.sender,
            farmer: _farmer,
            rating: _rating,
            comment: _comment,
            timestamp: block.timestamp,
            isVerifiedPurchase: _isVerifiedPurchase,
            orderId: _orderId
        });
        
        farmerReviews[_farmer].push(reviewCounter);
        
        FarmerProfile storage farmer = farmers[_farmer];
        farmer.ratingSum += _rating;
        farmer.ratingCount++;
        
        _updateTrustScore(_farmer);
        
        emit ReviewAdded(reviewCounter, _farmer, msg.sender, _rating);
        return reviewCounter;
    }
    
    function verifyFarmer(address _farmer) external onlyOwner {
        require(bytes(farmers[_farmer].farmerName).length > 0, "Farmer not registered");
        farmers[_farmer].isVerified = true;
        emit FarmerVerified(_farmer, msg.sender);
    }
    
    function unverifyFarmer(address _farmer) external onlyOwner {
        require(bytes(farmers[_farmer].farmerName).length > 0, "Farmer not registered");
        farmers[_farmer].isVerified = false;
        emit FarmerUnverified(_farmer, msg.sender);
    }
    
    function getFarmerProfile(address _farmer) external view returns (FarmerProfile memory) {
        return farmers[_farmer];
    }
    
    function getTrustTier(address _farmer) external view returns (string memory) {
        uint256 score = farmers[_farmer].trustScore;
        if (score >= EXCELLENT_THRESHOLD) return "Excellent";
        if (score >= GOOD_THRESHOLD) return "Good";
        if (score >= AVERAGE_THRESHOLD) return "Average";
        return "Poor";
    }
    
    function getFarmerReviews(address _farmer) external view returns (uint256[] memory) {
        return farmerReviews[_farmer];
    }
    
    function getReview(uint256 _reviewId) external view returns (Review memory) {
        return reviews[_reviewId];
    }
    
    function getTopFarmers(uint256 _count) external view returns (address[] memory) {
        uint256 count = _count > registeredFarmers.length ? registeredFarmers.length : _count;
        address[] memory topFarmers = new address[](count);
        
        for (uint256 i = 0; i < count; i++) {
            uint256 maxScore = 0;
            address maxFarmer;
            
            for (uint256 j = 0; j < registeredFarmers.length; j++) {
                address farmer = registeredFarmers[j];
                if (farmers[farmer].trustScore > maxScore) {
                    bool alreadyAdded = false;
                    for (uint256 k = 0; k < i; k++) {
                        if (topFarmers[k] == farmer) {
                            alreadyAdded = true;
                            break;
                        }
                    }
                    if (!alreadyAdded) {
                        maxScore = farmers[farmer].trustScore;
                        maxFarmer = farmer;
                    }
                }
            }
            topFarmers[i] = maxFarmer;
        }
        
        return topFarmers;
    }
    
    function addAuthorizedContract(address _contract) external onlyOwner {
        authorizedContracts[_contract] = true;
    }
    
    function removeAuthorizedContract(address _contract) external onlyOwner {
        authorizedContracts[_contract] = false;
    }
    
    function getRegisteredFarmersCount() external view returns (uint256) {
        return registeredFarmers.length;
    }
    
    function getAllRegisteredFarmers() external view returns (address[] memory) {
        return registeredFarmers;
    }
}
