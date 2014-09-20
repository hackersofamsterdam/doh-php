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

        $fractal     = new \League\Fractal\Manager;
        $class       = ucfirst($provider) . 'Transformer';
        $transformer = new $class;
        $resource    = new \League\Fractal\Resource\Item($result, $transformer);

        $data = $fractal->createData($resource)->toArray()['data'];

        $product = Product::find($id);

        $product->product_id  = $productId;
        $product->title       = $data['title'];
        $product->description = $data['description'];
        $product->provider    = $provider;
        $product->price       = $data['price'];
        $product->target      = $data['target'];

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