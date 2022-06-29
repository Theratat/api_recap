<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;


class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Review::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $request->validate([
            'product_id' => 'required',
            'rating' => 'required|integer|max:5|min:0'      
        ]);

        $review = Review::create([
            'user_id' => $request->user()->user_id,
            'product_id' => $request['product_id'],
            'review' => $request['review'],
            'rating' => $request['rating'],
            'tag' => $request['tag'],
        ]);

        $product = Product::find($request['product_id']);
        $count =  $product['review_count']+1;
        $rating = $product['rating']*$product['review_count']+$request['rating'];
        $rating = $rating/$count;
        $product->update([
            'rating' => $rating,
            'review_count' => $count,
        ]);


        return response($rating,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Review::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $review = Review::find($id);

        $request->validate([
            'rating' => 'required|integer|max:5|min:0'      
        ]);

        //update new rating
        $product = Product::find($request['product_id']);
        $rating = $product['rating']*$product['review_count']-$review['rating']+$request['rating'];
        $rating = $rating/$product['review_count'];
        $product->update([
            'rating' => $rating
        ]);
       
        $review->update($request->all()); 

        return $review;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = Review::find($id);

        //update new rating
        $product = Product::find($review->product_id);
        $count = $product['review_count']-1;
        $rating = $product['rating']*$product['review_count']-$review['rating'];
        $rating = $rating/$count;
        $product->update([
            'review_count' => $count,
            'rating' => $rating,
        ]);

        Review::destroy($id);

        return response([
            'massage' => 'Review '.$id.' has been delete.'
        ]);
    }
}
