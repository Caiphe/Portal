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
     * Logic for the search page
     *
     * @param      \Illuminate\Http\Request  $request  The request
     *
     * @return     \Illuminate\View\View     The view
     */
    public function __invoke(Request $request)
    {
        $page = $request->get('page', 1);
        if(!is_numeric($page)){
            $page = 1;
        }
        $page -= 1;
        $searchTerm = $request->get('q', '');
        if(!is_string($searchTerm)) $searchTerm = '';
        $query = '%' . $searchTerm . '%';
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
            ->hasSwagger()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', $query)
                    ->orWhere('display_name', 'like', $query)
                    ->orWhereHas('content', function ($q) use ($query) {
                        $q->where('body', 'like', $query);
                    });
            })
            ->get()
            ->map(function ($detail) {
                return ['title' => 'Product: ' . $detail['display_name'], 'description' => 'View the product', 'link' => "/products/{$detail['slug']}"];
            })
            ->toArray();

        $bundles = Bundle::with('content')
            ->where('display_name', 'like', $query)
            ->orWhere('description', 'like', $query)
            ->orWhereHas('content', function ($q) use ($query) {
                $q->where('body', 'like', $query);
            })
            ->get()
            ->map(function ($detail) {
                return ['title' => 'Bundle: ' . $detail['display_name'], 'description' => 'View the bundle', 'link' => "/bundles/{$detail['slug']}"];
            })->toArray();

        $faqs = Faq::where('question', 'like', $query)->orWhere('answer', 'like', $query)->get()->map(function ($detail) use ($searchTerm) {
            return ['title' => 'FAQ: ' . substr($detail['question'], 0, 80), 'description' => $this->findSearchTerm($detail['answer'], $searchTerm), 'link' => "/faq/#{$detail['slug']}"];
        })->toArray();

        $content = Content::whereIn('type', ['general_docs', 'page', 'blog'])
            ->where(function ($q) {
                $q->whereDoesntHaveMorph('contentable', [Product::class, Bundle::class])->orWhereNull('contentable_type');
            })
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', $query)->orWhere('body', 'like', $query);
            })
            ->get()
            ->map(function ($detail) use ($searchTerm) {
                $linkPrefix = [
                    'general_docs' => '/getting-started/',
                    'general_doc' => '/getting-started/',
                ][$detail['type']] ?? '/';

                $linkSlug = $detail['slug'];

                $contentType = ucfirst(explode('_', $detail['type'])[0]);

                return [
                    'title' => $contentType . ': ' . substr($detail['title'], 0, 80),
                    'description' => $this->findSearchTerm($detail['body'], $searchTerm),
                    'link' => $linkPrefix . $linkSlug
                ];
            })->reject(function ($value) {
                return count($value) === 0;
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

    /**
     * Finds a search term.
     *
     * @param      string  $content     The content
     * @param      string  $searchTerm  The search term
     *
     * @return     string  A sripped down version of the matched search terms
     */
    protected function findSearchTerm(string $content, string $searchTerm): string
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
