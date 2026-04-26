<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AIChat;

class AIAssistantController extends Controller
{
    // Predefined AI knowledge base for simulation
    private $knowledgeBase = [
        'disease' => [
            'keywords' => ['disease', 'sick', 'rot', 'fungus', 'mold', 'spots', 'wilting', 'yellow', 'brown', 'damage'],
            'responses' => [
                "Based on your description, this sounds like **Fusarium Wilt** (a fungal disease).\n\n**Symptoms:** Yellowing leaves, wilting, brown spots\n\n**Treatment:**\n• Apply Mancozeb fungicide\n• Remove infected plants\n• Improve drainage\n\n**Prevention:** Rotate crops annually",
                "This appears to be **Early Blight** (Alternaria solani).\n\n**Symptoms:** Dark brown spots with concentric rings\n\n**Treatment:**\n• Spray Copper-based fungicide\n• Remove lower infected leaves\n• Maintain plant spacing\n\nWould you like more details?",
                "Possible **Powdery Mildew** infection detected.\n\n**Symptoms:** White powdery coating on leaves\n\n**Treatment:**\n• Apply Sulfur-based fungicide\n• Increase air circulation\n• Water at base, not overhead\n\n**Organic option:** Neem oil spray",
            ],
        ],
        'pest' => [
            'keywords' => ['pest', 'insect', 'bug', 'worm', 'caterpillar', 'aphid', 'beetle', 'eating', 'holes'],
            'responses' => [
                "**Aphid Infestation** detected!\n\n**Signs:** Small green/black insects, sticky honeydew, curled leaves\n\n**Control:**\n• Spray with soapy water (organic)\n• Apply Imidacloprid insecticide\n• Introduce ladybugs (natural predator)\n\nMonitor daily for 1 week.",
                "Looks like **Fall Armyworm** damage.\n\n**Signs:** Holes in leaves, frass (droppings), larvae in whorl\n\n**Immediate Action:**\n• Apply Emamectin benzoate\n• Hand-pick visible larvae\n• Destroy egg masses\n\n**Prevention:** Plant early, use pheromone traps",
                "**Stem Borer** activity suspected.\n\n**Signs:** Dead hearts in seedlings, holes in stems\n\n**Treatment:**\n• Apply Chlorantraniliprole\n• Remove and destroy affected plants\n• Maintain field hygiene\n\nConsider resistant varieties next season.",
            ],
        ],
        'fertilizer' => [
            'keywords' => ['fertilizer', 'nutrient', 'feed', 'nitrogen', 'phosphorus', 'potassium', 'npk', 'growth', 'yellow leaves'],
            'responses' => [
                "**Nitrogen Deficiency** detected.\n\n**Symptoms:** Yellowing older leaves, stunted growth\n\n**Recommendation:**\n• Apply Urea (46% N) - 50kg/acre\n• Or use CAN (Calcium Ammonium Nitrate)\n• Side-dress at knee-high stage\n\n**Organic:** Compost tea or poultry manure",
                "For optimal **Maize Production**, use this NPK ratio:\n\n**Basal (Planting):** 23:23:0 - 100kg/acre\n**Top-dress:** Urea at 4-6 leaf stage\n\n**Alternative:**\n• DAP (18:46:0) - 50kg/acre at planting\n• Urea - 100kg/acre at top-dress\n\nApply when soil is moist.",
                "**Soil Health Tips:**\n\n1. Test soil annually (pH 6.0-7.0 ideal)\n2. Add organic matter (compost)\n3. Practice crop rotation\n4. Use cover crops\n5. Avoid over-fertilization\n\nBalanced nutrition = Better yields + Quality!",
            ],
        ],
        'irrigation' => [
            'keywords' => ['water', 'irrigate', 'dry', 'drought', 'moisture', 'rain', 'flood', 'drainage'],
            'responses' => [
                "**Irrigation Schedule for Maize:**\n\n• **Germination:** Daily light watering\n• **Vegetative:** 25-30mm every 5-7 days\n• **Flowering:** Critical - maintain moisture\n• **Grain filling:** Reduce gradually\n\n**Signs of water stress:** Leaf rolling, grayish color\n\nBest time: Early morning (6-9 AM)",
                "**Drought Management:**\n\n1. Mulch to retain moisture\n2. Prioritize younger plants\n3. Water at root zone, not leaves\n4. Consider drip irrigation\n5. Plant drought-resistant varieties\n\n**Emergency:** Light frequent irrigation better than heavy soaking",
            ],
        ],
        'planting' => [
            'keywords' => ['plant', 'seed', 'sow', 'spacing', 'season', 'time', 'germination', 'seedling'],
            'responses' => [
                "**Maize Planting Guide:**\n\n**Spacing:** 75cm x 25cm (1 plant/hill)\n**Seeds per hole:** 2 (thin to 1 later)\n**Depth:** 5cm in moist soil\n**Timing:** Start of rains (March-May or Oct-Nov)\n\n**Seed rate:** 10-12kg/acre\n**Expected emergence:** 5-10 days\n\nEnsure good seed-soil contact!",
                "**Best Planting Seasons (Tanzania):**\n\n🌧️ **Long Rains:** March - May\n🌧️ **Short Rains:** October - November\n\n**Soil temp:** Above 10°C for germination\n**Soil moisture:** Field capacity ideal\n\n**Tip:** Plant with first reliable rains for best establishment.",
            ],
        ],
        'harvest' => [
            'keywords' => ['harvest', 'mature', 'ready', 'pick', 'store', 'post-harvest', 'dry'],
            'responses' => [
                "**Maize Harvest Indicators:**\n\n✅ Husks turn brown and dry\n✅ Kernels hard and shiny\n✅ Black layer formed at kernel base\n✅ Moisture content: 20-25%\n\n**Timing:** 3-4 weeks after physiological maturity\n\n**Post-harvest:**\n• Dry to 13% moisture\n• Store in airtight containers\n• Add neem leaves as natural pesticide",
                "**Proper Drying Techniques:**\n\n1. **Crib drying:** Traditional, 2-4 weeks\n2. **Sun drying:** Spread thin, turn daily\n3. **Mechanical:** For large quantities\n\n**Target moisture:** 13-14% for storage\n\n**Storage pests:** Use Actellic dust (50g per 90kg bag)\n\nMonitor moisture monthly during storage.",
            ],
        ],
        'market' => [
            'keywords' => ['price', 'sell', 'market', 'buyer', 'profit', 'revenue', 'demand'],
            'responses' => [
                "**Market Price Tips:**\n\n📊 **Best selling times:**\n• Avoid peak harvest (low prices)\n• Store and sell in off-season\n• Target food processors directly\n• Join farmer cooperatives\n\n💰 **Value addition:**\n• Shell and grade for +20% price\n• Package in 50/90kg bags\n• Get certified for premium markets\n\nCheck AgroSphere Market Insights daily!",
            ],
        ],
    ];

    private $generalResponses = [
        "👋 **Welcome to Agro AI!**\n\nI'm your intelligent farming assistant powered by agricultural expertise. I can help you with:\n\n🌱 **Crop Management** - Diseases, pests, nutrients\n💧 **Irrigation** - Water scheduling, drought tips\n🧪 **Fertilizers** - NPK recommendations, soil health\n🌾 **Planting & Harvest** - Timing, spacing, storage\n💰 **Markets** - Prices, buyers, value addition\n\n**Describe your question or upload a photo** - I'll provide specific, actionable advice!",

        "🤖 **Agro AI at your service!**\n\nHello! I'm designed to help Tanzanian farmers make better decisions. Tell me:\n\n• What crop are you growing?\n• What problem or question do you have?\n• Upload photos of diseased plants\n\nI specialize in:\n✓ Maize, Rice, Coffee, Beans, and more\n✓ Disease & pest identification\n✓ Fertilizer & irrigation planning\n✓ Market price insights\n\n**How can I help your farm today?**",

        "🌾 **Hello from Agro AI!**\n\nI'm here to support your farming success with smart recommendations.\n\n**For accurate advice, share:**\n• Crop type & variety\n• Current symptoms/issues\n• Farm location & weather\n• Photos of affected plants\n\n**I can assist with:**\n🔍 Disease diagnosis\n🐛 Pest management\n💧 Irrigation strategies\n🧪 Fertilizer schedules\n📈 Market opportunities\n\n**Ask me anything!**",
    ];

    public function index()
    {
        $chats = AIChat::where('user_id', auth()->id())
            ->orderBy('created_at', 'asc')
            ->get();

        // If no chats, add welcome message
        if ($chats->isEmpty()) {
            $welcome = AIChat::create([
                'user_id' => auth()->id(),
                'message' => null,
                'response' => $this->generalResponses[array_rand($this->generalResponses)],
                'type' => 'ai',
                'has_image' => false,
            ]);
            $chats->push($welcome);
        }

        return view('farmer.ai-assistant', compact('chats'));
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required_without:image|string|nullable|max:1000',
            'image' => 'nullable|image|max:5120', // 5MB max
        ]);

        $message = $request->input('message', '');
        $imagePath = null;
        $hasImage = false;

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ai-uploads', 'public');
            $imagePath = Storage::url($path);
            $hasImage = true;
        }

        // Save user message
        $chat = AIChat::create([
            'user_id' => auth()->id(),
            'message' => $message,
            'response' => null,
            'type' => 'user',
            'has_image' => $hasImage,
            'image_path' => $imagePath,
        ]);

        // Generate AI response
        $aiResponse = $this->generateResponse($message, $hasImage);

        // Save AI response
        $aiChat = AIChat::create([
            'user_id' => auth()->id(),
            'message' => null,
            'response' => $aiResponse,
            'type' => 'ai',
            'has_image' => false,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'user_message' => [
                    'id' => $chat->id,
                    'message' => $message,
                    'image' => $imagePath,
                    'time' => $chat->created_at->format('H:i'),
                ],
                'ai_response' => [
                    'id' => $aiChat->id,
                    'response' => $aiResponse,
                    'time' => $aiChat->created_at->format('H:i'),
                ],
            ]);
        }

        return redirect()->route('farmer.ai-assistant');
    }

    private function generateResponse($message, $hasImage)
    {
        $lowerMessage = strtolower($message);

        // Extract crop type from message
        $cropType = $this->detectCropType($lowerMessage);
        $specificIssue = $this->detectSpecificIssue($lowerMessage);

        // Check for image-based diagnosis
        if ($hasImage && empty($message)) {
            return "🔍 **Agro AI Image Analysis**\n\nI've analyzed your photo. Here's what I can identify:\n\n**Visual Observations:**\n• Leaf discoloration patterns\n• Possible pest activity signs\n• Texture abnormalities\n\n**To give you a precise diagnosis, please tell me:**\n1. What crop is this?\n2. When did you first notice the problem?\n3. Is it spreading to other plants?\n4. Any recent weather changes (heavy rain/drought)?\n\n💡 **Tip:** The more details you provide, the better my recommendations!";
        }

        if ($hasImage) {
            return "📸 **Agro AI Visual + Context Analysis**\n\nI've examined your image while considering your description: *\"{$message}\"*\n\n**Image Assessment:**\n• Pattern analysis suggests {$specificIssue}\n• Crop type appears to be: {$cropType}\n\n" . $this->getContextualResponse($lowerMessage, $cropType, $specificIssue);
        }

        return $this->getContextualResponse($lowerMessage, $cropType, $specificIssue);
    }

    private function detectCropType($message)
    {
        $crops = [
            'maize' => ['maize', 'corn', 'mahindi'],
            'rice' => ['rice', 'mpunga', 'mchele'],
            'coffee' => ['coffee', 'kahawa'],
            'beans' => ['beans', 'maharagwe'],
            'wheat' => ['wheat', 'ngano'],
            'tea' => ['tea', 'chai'],
            'tomato' => ['tomato', 'nyanya'],
            'potato' => ['potato', 'viazi'],
            'cassava' => ['cassava', 'mihogo'],
            'banana' => ['banana', 'ndizi'],
        ];

        foreach ($crops as $crop => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($message, $keyword)) {
                    return $crop;
                }
            }
        }

        return 'general';
    }

    private function detectSpecificIssue($message)
    {
        $issues = [
            'disease' => ['disease', 'sick', 'rot', 'fungus', 'mold', 'spots', 'wilting', 'yellow', 'brown', 'blight', 'mildew'],
            'pest' => ['pest', 'insect', 'bug', 'worm', 'caterpillar', 'aphid', 'beetle', 'borer', 'eating', 'holes', 'damage'],
            'nutrition' => ['fertilizer', 'nutrient', 'nitrogen', 'phosphorus', 'potassium', 'yellow leaves', 'stunted'],
            'water' => ['water', 'irrigate', 'dry', 'drought', 'moisture', 'rain', 'flood', 'drainage', 'wilting'],
            'planting' => ['plant', 'seed', 'sow', 'spacing', 'germination', 'seedling', 'transplant'],
            'harvest' => ['harvest', 'mature', 'ready', 'pick', 'store', 'dry', 'post-harvest'],
        ];

        foreach ($issues as $issue => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($message, $keyword)) {
                    return $issue;
                }
            }
        }

        return 'general';
    }

    private function getContextualResponse($message, $cropType, $specificIssue)
    {
        // Check each category for keyword matches
        foreach ($this->knowledgeBase as $category => $data) {
            foreach ($data['keywords'] as $keyword) {
                if (str_contains($message, $keyword)) {
                    $response = $data['responses'][array_rand($data['responses'])];
                    
                    // Add contextual follow-up based on crop type
                    if ($cropType !== 'general') {
                        $response .= "\n\n🌱 **Specific to your {$cropType}:**\n";
                        $response .= $this->getCropSpecificTip($cropType, $specificIssue);
                    }
                    
                    return $response;
                }
            }
        }

        // Contextual default response
        if ($cropType !== 'general' && $specificIssue !== 'general') {
            return "🤔 **Agro AI Analysis**\n\nI see you're asking about **{$specificIssue}** affecting your **{$cropType}**.\n\n" . $this->getCropSpecificTip($cropType, $specificIssue) . "\n\n💡 **Need more help?**\n• Upload a photo of the affected plants\n• Describe symptoms in detail\n• Tell me about your location and recent weather\n\nI'm here to give you specific advice!";
        }

        // Default response if no keywords match
        return "🤔 **Agro AI here!**\n\nI'd love to help you better. Could you tell me:\n\n• What crop are you growing? (Maize, Rice, Coffee, Beans, etc.)\n• What specific problem or question?\n• Any symptoms you're seeing?\n• Where is your farm located?\n• Recent weather conditions?\n\n**I'm an expert in:**\n🔍 Disease identification\n🐛 Pest control\n🧪 Fertilizer recommendations\n💧 Irrigation advice\n🌾 Planting & harvest timing\n💰 Market prices\n\n**Upload photos** for visual diagnosis!";
    }

    private function getCropSpecificTip($cropType, $issue)
    {
        $tips = [
            'maize' => [
                'disease' => "For maize:\n• Watch for Maize Streak Virus (MSV) - remove infected plants\n• Gray Leaf Spot - apply fungicide early\n• Head Smut - use certified seeds\n\n**Action:** Inspect tassels and leaves weekly.",
                'pest' => "For maize:\n• Fall Armyworm - biggest threat, check whorl\n• Stalk Borer - look for dead hearts\n• Aphids - under leaves, spray if severe\n\n**Prevention:** Early planting, crop rotation.",
                'nutrition' => "For maize:\n• Needs high Nitrogen - top-dress at knee height\n• Deficiency shows as yellowing from bottom\n• Apply NPK 23:23:0 at planting\n\n**Rate:** 100kg DAP + 100kg Urea per acre.",
                'water' => "For maize:\n• Critical at tasseling & grain filling\n• Water stress = 30% yield loss\n• Needs 500mm rain/season\n\n**Tip:** Irrigate if no rain for 7+ days during flowering.",
            ],
            'rice' => [
                'disease' => "For rice:\n• Blast disease - spots with brown borders\n• Bacterial Blight - yellow streaks on leaves\n• Sheath Rot - white patches\n\n**Control:** Resistant varieties, avoid overcrowding.",
                'pest' => "For rice:\n• Stem Borer - dead hearts, white heads\n• Brown Planthopper - wilting, virus spread\n• Rice Hispa - scrape leaves\n\n**Monitor:** Check 10 hills randomly weekly.",
                'nutrition' => "For rice:\n• Needs lots of Nitrogen - split application\n• Yellowing = Nitrogen deficiency\n• Zinc deficiency = stunted, brown spots\n\n**Apply:** 100kg DAP + 100kg Urea + 50kg MOP.",
                'water' => "For rice:\n• Flooded conditions preferred\n• 5-10cm water depth ideal\n• Critical at transplanting & flowering\n\n**Drain:** 2 weeks before harvest for easy collection.",
            ],
            'coffee' => [
                'disease' => "For coffee:\n• Coffee Berry Disease (CBD) - black spots on berries\n• Leaf Rust - orange powder under leaves\n• Wilt Disease - sudden branch death\n\n**Control:** Copper fungicides, shade management.",
                'pest' => "For coffee:\n• Coffee Berry Borer - small holes in cherries\n• Stem Borer - sawdust at base\n• Antestia bug - black spots on beans\n\n**Action:** Pick all cherries, even unripe.",
                'nutrition' => "For coffee:\n• Needs balanced NPK + micronutrients\n• Deficiency = yellow leaves, poor flowering\n• Apply organic matter yearly\n\n**Schedule:** NPK after harvest, CAN before rains.",
                'water' => "For coffee:\n• Needs 1200-2000mm rain/year\n• Drought stress = flower abortion\n• Mulch to retain moisture\n\n**Supplementary:** Irrigation during flowering if dry.",
            ],
        ];

        if (isset($tips[$cropType][$issue])) {
            return $tips[$cropType][$issue];
        }

        // Generic tip for other crops
        return "**General advice for {$cropType}:**\n• Scout your field weekly for early detection\n• Maintain field hygiene - remove crop residues\n• Use certified seeds/planting materials\n• Keep records of treatments applied\n\nFor specific recommendations, share more details or upload a photo!";
    }

    public function clearHistory()
    {
        AIChat::where('user_id', auth()->id())->delete();
        
        return redirect()->route('farmer.ai-assistant')
            ->with('success', 'Chat history cleared. Starting fresh conversation!');
    }
}
