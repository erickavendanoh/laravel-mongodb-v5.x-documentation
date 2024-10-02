<div>
    <h2>Find Multiple Documents</h2>
    <br/>
    @forelse ($movies as $movie)
        <p>
            Title: {{ $movie->title }}<br>
            Year: {{ $movie->year }}<br>
            Runtime: {{ $movie->runtime }}<br>
            IMDB Rating: {{ $movie->imdb['rating'] }}<br>
            IMDB Votes: {{ $movie->imdb['votes'] }}<br>
            Plot: {{ $movie->plot }}<br>
        </p>
    @empty
        <p>No results</p>
    @endforelse
</div>
