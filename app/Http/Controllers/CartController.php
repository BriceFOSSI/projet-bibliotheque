namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Http\Requests\OrderRequest;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::all();
        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        CartItem::create($request->all());
        return redirect()->back()->with('success', 'Produit ajouté au panier.');
    }

    public function remove($id)
    {
        CartItem::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Produit supprimé.');
    }

    public function checkout(OrderRequest $request)
    {
        // Ici tu pourrais enregistrer la commande complète dans une table "orders"
        CartItem::truncate(); // vider le panier après validation
        return redirect()->route('cart.index')->with('success', 'Commande validée !');
    }
}
