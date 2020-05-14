<?php

namespace App\Http\Controllers;

use App\Content;
use App\Faq;
use App\Product;
use App\Bundle;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $searchTerm = $request->get('q', '');
        $query = '%' . $searchTerm . '%';
        $page = $request->get('page', 1) - 1;
        $length = 12;

        if (empty($searchTerm)) {
            return view('templates.search', [
                'results' => [],
                'searchTerm' => $searchTerm,
                'total' => 0,
                'page' => 0,
                'pages' => 0
            ]);
        }

        $products = Product::basedOnUser($request->user())
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', $query)->orWhere('display_name', 'like', $query);
            })
            ->get()
            ->map(function ($detail) {
                return ['title' => 'Product: ' . $detail['display_name'], 'description' => 'View the product', 'link' => "/products/{$detail['slug']}"];
            })
            ->toArray();

        $bundles = Bundle::with('content')
            ->where('display_name', 'like', $query)
            ->orWhere('description', 'like', $query)
            ->get()
            ->map(function ($detail) {
                return ['title' => 'Bundle: ' . $detail['display_name'], 'description' => 'View the product', 'link' => "/products/{$detail['slug']}"];
            })->toArray();

        $faqs = Faq::where('question', 'like', $query)->orWhere('answer', 'like', $query)->get()->map(function ($detail) use ($searchTerm) {
            return ['title' => 'FAQ: ' . substr($detail['question'], 0, 80), 'description' => $this->findSearchTerm($detail['answer'], $searchTerm), 'link' => "/faq/#{$detail['slug']}"];
        })->toArray();

        $content = Content::with('product')->where('title', 'like', $query)->orWhere('body', 'like', $query)->get()->map(function ($detail) use ($searchTerm) {
            $linkPrefix = [
                'general_docs' => '/getting-started/',
                'general_doc' => '/getting-started/',
                'product_docs' => '/products/',
                'product_doc' => '/products/',
                'product_overview' => '/products/',
                'bundle_overview' => '/bundles/',
            ][$detail['type']] ?? '/';

            $linkSufix = [
                'product_docs' => '/#/docs',
                'product_doc' => '/#/docs',
                'product_overview' => '/#/overview',
            ][$detail['type']] ?? '';

            $linkSlug = $detail['slug'];
            if (!$detail->product->isEmpty()) {
                $linkSlug = $detail->product[0]['slug'];
            }

            $contentType = ucfirst(explode('_', $detail['type'])[0]);

            return [
                'title' => $contentType . ': ' . substr($detail['title'], 0, 80),
                'description' => $this->findSearchTerm($detail['body'], $searchTerm),
                'link' => $linkPrefix . $linkSlug . $linkSufix
            ];
        })->toArray();

        $results = array_merge($products, $faqs, $content, $bundles);
        $total = count($results);

        return view('templates.search', [
            'results' => array_slice($results, ($page * $length), $length),
            'searchTerm' => $searchTerm,
            'total' => $total,
            'page' => $page,
            'pages' => (int) ceil($total / $length)
        ]);
    }

    protected function findSearchTerm($content, $searchTerm)
    {
        $c = preg_replace('/\n|\s+/', ' ', strip_tags($content));
        $termAt = stripos($c, $searchTerm);
        if (!$termAt) {
            return substr($c, 0, 200);
        }

        $halfLength = 100;

        if ($termAt < $halfLength) {
            $prefix = substr($c, 0, $termAt);
            $halfLength += $halfLength - $termAt;
        } else {
            $prefix = substr($c, ($termAt - $halfLength), $halfLength);
        }

        $match = '<span class="highlight">' . substr($c, $termAt, strlen($searchTerm)) . '</span>';
        $sufix = substr($c, ($termAt + strlen($searchTerm)), $halfLength);

        return $prefix . $match . $sufix;
    }
}
