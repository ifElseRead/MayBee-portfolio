<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
    <channel>
        <title>{{ config('app.name') }} Blog</title>
        <link>{{ url('/') }}</link>
        <description>Latest technical posts and tutorials</description>
        @foreach ($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ url('/posts/' . $post->slug) }}</link>
                <description>{{ $post->summary }}</description>
                <pubDate>{{ $post->created_at->toRfc2822String() }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss>
