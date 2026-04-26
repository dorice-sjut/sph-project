<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Blockchain Network Configuration
    |--------------------------------------------------------------------------
    |
    | Supported networks: mainnet, sepolia, base, base_sepolia, localhost
    |
    */
    'network' => env('BLOCKCHAIN_NETWORK', 'sepolia'),
    
    /*
    |--------------------------------------------------------------------------
    | RPC Provider URLs
    |--------------------------------------------------------------------------
    */
    'rpc_urls' => [
        'mainnet' => env('ETH_MAINNET_RPC', 'https://eth-mainnet.g.alchemy.com/v2/'),
        'sepolia' => env('ETH_SEPOLIA_RPC', 'https://eth-sepolia.g.alchemy.com/v2/'),
        'base' => env('BASE_RPC', 'https://base-mainnet.g.alchemy.com/v2/'),
        'base_sepolia' => env('BASE_SEPOLIA_RPC', 'https://base-sepolia.g.alchemy.com/v2/'),
        'localhost' => 'http://127.0.0.1:8545',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Alchemy API Key
    |--------------------------------------------------------------------------
    */
    'alchemy_key' => env('ALCHEMY_API_KEY'),
    
    /*
    |--------------------------------------------------------------------------
    | Wallet Configuration
    |--------------------------------------------------------------------------
    */
    'wallet' => [
        'private_key' => env('BLOCKCHAIN_PRIVATE_KEY'),
        'address' => env('BLOCKCHAIN_WALLET_ADDRESS'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Smart Contract Addresses
    |--------------------------------------------------------------------------
    | These are the deployed contract addresses on your chosen network
    */
    'contracts' => [
        'origin' => [
            'address' => env('ORIGIN_CONTRACT_ADDRESS'),
            'abi' => base_path('blockchain/abis/AgroSphereOrigin.json'),
        ],
        'escrow' => [
            'address' => env('ESCROW_CONTRACT_ADDRESS'),
            'abi' => base_path('blockchain/abis/AgroSphereEscrow.json'),
        ],
        'reputation' => [
            'address' => env('REPUTATION_CONTRACT_ADDRESS'),
            'abi' => base_path('blockchain/abis/AgroSphereReputation.json'),
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Gas Settings
    |--------------------------------------------------------------------------
    */
    'gas' => [
        'limit' => env('BLOCKCHAIN_GAS_LIMIT', 300000),
        'price_gwei' => env('BLOCKCHAIN_GAS_PRICE', 20), // For legacy transactions
        'max_fee_gwei' => env('BLOCKCHAIN_MAX_FEE', 50),
        'max_priority_gwei' => env('BLOCKCHAIN_MAX_PRIORITY', 2),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Confirmation Settings
    |--------------------------------------------------------------------------
    */
    'confirmations' => [
        'required' => env('BLOCKCHAIN_CONFIRMATIONS', 12),
        'timeout' => env('BLOCKCHAIN_TIMEOUT', 300), // seconds
        'polling_interval' => 5, // seconds
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Platform Settings
    |--------------------------------------------------------------------------
    */
    'platform' => [
        'fee_percentage' => 2.5, // 2.5%
        'treasury_address' => env('TREASURY_WALLET_ADDRESS'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    */
    'enabled' => env('BLOCKCHAIN_ENABLED', true),
    'test_mode' => env('BLOCKCHAIN_TEST_MODE', false), // Use test accounts
    
    /*
    |--------------------------------------------------------------------------
    | IPFS Configuration (for document storage)
    |--------------------------------------------------------------------------
    */
    'ipfs' => [
        'enabled' => env('IPFS_ENABLED', false),
        'gateway' => env('IPFS_GATEWAY', 'https://ipfs.io/ipfs/'),
        'pinata_api_key' => env('PINATA_API_KEY'),
        'pinata_secret' => env('PINATA_SECRET'),
    ],
];
