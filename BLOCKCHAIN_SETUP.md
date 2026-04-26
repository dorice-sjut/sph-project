# AgroSphere Blockchain Integration Setup

This document outlines the blockchain integration for AgroSphere using Ethereum/Base-compatible networks.

## Overview

AgroSphere now includes on-chain functionality for:

1. **Product Origin Verification** - Immutable proof of product authenticity
2. **Escrow Payment System** - Secure payment release upon delivery
3. **Farmer Reputation** - Trust scores stored on blockchain

## Smart Contracts

### 1. AgroSphereOrigin.sol
- **Purpose**: Verify farm product origin
- **Features**:
  - Batch ID tracking
  - Farmer verification
  - Harvest date recording
  - Organic certification status
  - IPFS document linking

### 2. AgroSphereEscrow.sol
- **Purpose**: Secure payment handling
- **Features**:
  - Buyer funds locking
  - Delivery confirmation
  - Automatic farmer payment release
  - Platform fee handling (2.5%)
  - Refund mechanism

### 3. AgroSphereReputation.sol
- **Purpose**: Farmer trust scoring
- **Features**:
  - Transaction history
  - Success/failure tracking
  - Review system (1-5 stars)
  - Trust tier calculation
  - Top farmer leaderboards

## Configuration

Add to your `.env` file:

```env
# Blockchain Configuration
BLOCKCHAIN_ENABLED=true
BLOCKCHAIN_NETWORK=sepolia  # Options: mainnet, sepolia, base, base_sepolia, localhost
BLOCKCHAIN_TEST_MODE=false

# Alchemy API (for RPC access)
ALCHEMY_API_KEY=your_alchemy_api_key_here

# Wallet Configuration (Deployer wallet)
BLOCKCHAIN_WALLET_ADDRESS=0x...
BLOCKCHAIN_PRIVATE_KEY=0x...  # Keep secure!

# Deployed Contract Addresses (after deployment)
ORIGIN_CONTRACT_ADDRESS=0x...
ESCROW_CONTRACT_ADDRESS=0x...
REPUTATION_CONTRACT_ADDRESS=0x...

# Treasury/Platform Wallet (receives fees)
TREASURY_WALLET_ADDRESS=0x...

# Gas Settings
BLOCKCHAIN_GAS_LIMIT=300000
BLOCKCHAIN_MAX_FEE=50
BLOCKCHAIN_MAX_PRIORITY=2

# IPFS (optional, for document storage)
IPFS_ENABLED=false
PINATA_API_KEY=your_pinata_key
PINATA_SECRET=your_pinata_secret
```

## Database Migrations

Run the blockchain-related migrations:

```bash
php artisan migrate
```

This will create:
- `blockchain_transactions` table
- Add blockchain fields to `products`, `orders`, and `users` tables

## Deployment Steps

### 1. Install Dependencies

```bash
composer require sc0vu/web3-php dev-master
npm install -g truffle  # For contract compilation
```

### 2. Compile Contracts

```bash
cd blockchain/
truffle compile
```

### 3. Deploy to Testnet

```bash
truffle migrate --network sepolia
```

Copy the deployed contract addresses to your `.env` file.

### 4. Verify Contracts (Optional)

Use Etherscan/Basescan API to verify source code.

## Testing Mode

For development without real blockchain:

```env
BLOCKCHAIN_ENABLED=true
BLOCKCHAIN_TEST_MODE=true
```

This simulates all blockchain operations without actual transactions.

## API Endpoints

### Public Endpoints
- `GET /api/blockchain/verify-batch?batch_id=XXX` - Verify product by batch ID
- `GET /api/blockchain/stats` - Get blockchain statistics
- `GET /api/blockchain/verified-products` - List verified products
- `GET /api/blockchain/top-farmers` - Get top farmers by reputation

### Authenticated Endpoints

#### Farmers
- `POST /blockchain/verify-product/{product}` - Verify product
- `POST /blockchain/register-farmer` - Register on reputation system

#### Buyers
- `POST /blockchain/escrow/{order}/create` - Create escrow payment
- `POST /blockchain/escrow/{order}/confirm-delivery` - Confirm delivery & release payment

### Admin Endpoints
- `GET /admin/blockchain` - Blockchain dashboard
- `GET /admin/blockchain/transactions` - All transactions
- `GET /admin/blockchain/products` - Verified products
- `GET /admin/blockchain/farmers` - Registered farmers

## UI Components

### Blockchain Badge
```blade
<x-blockchain-badge 
    :verified="$product->is_blockchain_verified" 
    :batchId="$product->batch_id" 
    :txHash="$product->blockchain_tx_hash" 
/>
```

### Farmer Reputation Badge
```blade
<x-farmer-reputation-badge :user="$farmer" show-details />
```

### Product Verification Modal
```blade
<x-verify-product-modal :product="$product" />
```

## Security Considerations

1. **Private Key Storage**: Never commit private keys to version control
2. **Wallet Security**: Use hardware wallets for production
3. **Contract Ownership**: Transfer ownership to multi-sig wallet after deployment
4. **Rate Limiting**: Implement rate limiting on blockchain API endpoints
5. **Monitoring**: Set up alerts for suspicious activity

## Gas Costs (Estimated)

- Product Verification: ~50,000 gas (~$1-3 on Sepolia)
- Escrow Creation: ~100,000 gas (~$2-5 on Sepolia)
- Delivery Confirmation: ~30,000 gas (~$1-2 on Sepolia)
- Farmer Registration: ~40,000 gas (~$1-3 on Sepolia)

## Troubleshooting

### Web3 Connection Issues
- Check RPC URL in configuration
- Verify Alchemy API key
- Ensure correct network selection

### Transaction Failures
- Check wallet balance for gas fees
- Verify contract addresses are correct
- Check for sufficient gas limit

### Database Issues
- Ensure all migrations have run
- Check for proper field types
- Verify foreign key constraints

## Support

For issues or questions:
- Check the `/admin/blockchain` dashboard for status
- Review `blockchain_transactions` table for failed transactions
- Enable debug logging: `LOG_LEVEL=debug`

## License

The smart contracts are licensed under MIT.
