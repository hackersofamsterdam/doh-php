<?php

class ProductController extends Controller
{
    public function updateProductsAction()
    {
        $user = Auth::user();

        $config = Config::get('providers.' . $user->provider);

        $client = new \Guzzle\Http\Client();

        $headers = $config['headers'];

        array_walk($headers, function (&$value, $key) use ($user) {
            $value = str_replace('{token}', $user->access_token, $value);
        });

        $request = $client->get(str_replace('{blog_id}', $user->blog_id, $config['mediaUri']));

        $request->setHeaders($headers);

        $request->getHeaders()->toArray();

        $response = $request->send();

        $images = $response->json()['media'];

        $products = [];

        foreach ($images as $image) {
            if (!$product = Product::where('image_id', '=', $image['id'])->first()) {
                $product = new Product;

                $product->image_id = $image['id'];
                $product->image    = $image['link'];
                $product->title    = $image['title'];

                $user->products()->save($product);

                $products[] = $product;
            }
        }

        return Redirect::route('products');
    }

    public function updateProductAction($id)
    {
        list($provider, $productId) = explode('-', Input::get('token'));

        $result = (new $provider)->product($productId);

        $product = Product::find($id);

        $product->product_id = $productId;
        $product->title = $result['title'];
        $product->description = $result['description'];
        $product->provider = $provider;
        $product->price = $result['price'];
        $product->target = $result['target'];

        $product->save();

        return Redirect::route('products');
    }

    public function productsAction()
    {
        return View::make('products', [
            'products' => Product::all()
        ]);
    }
}