import {ShoppingCart, Sparkles, Star} from 'lucide-react';

const PromptCard = ({ prompt, onAddToCart }) => {
    // We're creating a mockup with sample data since the original component was imported
    const samplePrompt = {
        id: prompt.id || '1',
        title: prompt.title || 'Cyberpunk Character Design',
        description: prompt.description || 'Create stunning cyberpunk character concepts with detailed augmentations and futuristic urban backdrop.',
        rating: prompt.rating || 4.8,
        price: prompt.price || 12.99,
        image: prompt.image || '/api/placeholder/400/300',
        category: prompt.category || 'creative',
        popular: prompt.popular || true
    };

    return (
        <div className="bg-gray-800 bg-opacity-80 backdrop-blur-sm rounded-xl overflow-hidden border border-gray-700 hover:border-indigo-600 transition-all shadow-lg hover:shadow-indigo-900/30 group">
            <div className="relative">
                <img src={samplePrompt.image} alt={samplePrompt.title} className="w-full h-48 object-cover" />
                {samplePrompt.popular && (
                    <div className="absolute top-3 right-3 bg-indigo-600 text-white text-xs px-2 py-1 rounded-full flex items-center">
                        <Sparkles size={12} className="mr-1" />
                        Popular
                    </div>
                )}
            </div>
            <div className="p-5 space-y-3">
                <div className="flex justify-between items-start">
                    <h3 className="text-lg font-semibold line-clamp-1">{samplePrompt.title}</h3>
                    <div className="flex items-center text-yellow-400">
                        <Star size={16} className="fill-current" />
                        <span className="ml-1 text-sm">{samplePrompt.rating}</span>
                    </div>
                </div>
                <p className="text-gray-400 text-sm line-clamp-2">{samplePrompt.description}</p>
                <div className="flex justify-between items-center pt-2">
                    <span className="text-lg font-bold text-white">${samplePrompt.price}</span>
                    <button
                        onClick={() => onAddToCart(samplePrompt)}
                        className="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg text-sm transition-colors flex items-center"
                    >
                        <ShoppingCart size={16} className="mr-1" />
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    );
};

export default PromptCard;
