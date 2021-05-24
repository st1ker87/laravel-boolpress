<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use App\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ['posts' => Post::all()];

        return view('admin.posts.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'tags' => Tag::all(),
            'categories' => Category::all()
        ];
        return view('admin.posts.create', $data);
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
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $form_data = $request->all();

        $new_post = new Post();
        
        $new_post->fill($form_data);
        // genero slug
        $slug = Str::slug($new_post->title);
        $slug_base = $slug;
        // verifico se slug è già presente nel db
        $post_presente = Post::where('slug', $slug)->first();
        $contatore = 1;
        // se ho uno slug uguale entro nel while
        while ($post_presente){
            // genero nuovo slug con contatore alla fine
            $slug = $slug_base . '-' . $contatore;
            $contatore++;
            $post_presente = Post::where('slug', $slug)->first();
        }
        // ora sono sicuro che lo slug generato è unico
        // assegno lo slug al post
        $new_post->slug = $slug;
        $new_post->user_id = Auth::id();
        $new_post->save();

        // verifico se sono stati selezionati tags
        if(array_key_exists('tags', $form_data)){
            $new_post->tags()->sync($form_data['tags']);
        }

        return redirect()->route('admin.posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::where('id', $id)->first();
        if (!$post) {
            abort(404);
        }
        $data = ['post' => $post];
        return view('admin.posts.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if(!$post){
            abort(404);
        }
        $data = [
            'post' => $post,
            'tags' => Tag::all()
        ];
        return view('admin.posts.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);
        $form_data = $request->all();

        if($form_data['title'] != $post->title) {
            $slug = Str::slug($form_data['title']);
            $slug_base = $slug;

            $post_presente = Post::where('slug', $slug)->first();
            $contatore = 1;

            while ($post_presente) {
                $slug = $slug_base . '-' . $contatore;
                $contatore++;
                $post_presente = Post::where('slug', $slug)->first();
            }

            $form_data['slug'] = $slug;
        }

        $post->update($form_data);

        if(array_key_exists('tags', $form_data)){
            $post->tags()->sync($form_data['tags']);
        } else {
            $post->tags()->sync([]);
        }

        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->tags()->sync([]);
        $post->delete();
        return redirect()->route('admin.posts.index');
    }
}
