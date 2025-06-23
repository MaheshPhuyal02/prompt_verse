import {ShoppingCart, Sparkles, Star} from 'lucide-react';
import {addToCart} from "../api/api.js";

const PromptCard = ({ prompt }) => {
    // We're creating a mockup with sample data since the original component was imported

    const onAddToCart = async (prompt) => {
        try {
            const result = await addToCart(prompt);
            if (result.success) {
                alert("Prompt added to cart successfully!");
            } else {
                alert("Failed to add prompt to cart.");
            }
        } catch (error) {
            console.error("Error adding prompt to cart:", error);
            alert("An error occurred while adding the prompt to the cart.");
        }
    }
    const imageUrl =
        "http://127.0.0.1:8000/api/file/" + prompt.image;

    return (
        <div className="bg-gray-800 bg-opacity-80 backdrop-blur-sm rounded-xl overflow-hidden border border-gray-700 hover:border-indigo-600 transition-all shadow-lg hover:shadow-indigo-900/30 group">
            <div className="relative">
                <img src={imageUrl} alt={prompt.title} className="w-full h-48 object-cover" />
                {prompt.popular && (
                    <div className="absolute top-3 right-3 bg-indigo-600 text-white text-xs px-2 py-1 rounded-full flex items-center">
                        <Sparkles size={12} className="mr-1" />
                        Popular
                    </div>
                )}
            </div>
            <div className="p-5 space-y-3">
                <div className="flex justify-between items-start">
                    <h3 className="text-lg font-semibold line-clamp-1">{prompt.title}</h3>

                </div>
                <p className="text-gray-400 text-sm line-clamp-2">{prompt.description}</p>
                <div className="flex justify-between items-center pt-2">
                    <span className="text-lg font-bold text-white">Rs{prompt.price}</span>
                    <button
                        onClick={() => onAddToCart(prompt)}
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
