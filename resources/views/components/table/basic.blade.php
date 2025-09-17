<x-layout.container>
    <h4>{{$name}}</h4>
    <table class="table table-light table-striped table-hover">
        <thead>
        <tr>
            @foreach($columns as $column)
                <th>{{$column}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        {{ $slot }}
        </tbody>
    </table>
</x-layout.container>
