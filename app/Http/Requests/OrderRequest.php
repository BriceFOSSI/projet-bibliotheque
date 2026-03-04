namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string|max:500',
            'cart' => 'required|array|min:1',
            'cart.*.product_name' => 'required|string',
            'cart.*.price' => 'required|numeric|min:0',
            'cart.*.quantity' => 'required|integer|min:1',
        ];
    }
}
