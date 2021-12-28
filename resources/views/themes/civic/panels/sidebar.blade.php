<!-- Right Sidebar -->
<div class="col-xl-4 col-lg-4 col-md-4 col-md-4 col-12 mb-2">
    <ul class="list-group contact-list">
        <div class="list-group-title">Related Post</div>

        @foreach ($sidebar as $item)
        <li class="list-group-item">
            <a href="{{ route('post.show',$item->slug) }}" title="{{ $item->post_title }}"> {{ $loop->iteration }}. {{
                $item->post_title }}
                <i class="fa fa-external-link" aria-hidden="true"></i></a>
        </li>
        @endforeach
    </ul>

    <ul class="list-group contact-list trouble">
        <div class="list-group-title">Troubleshoot</div>
        <li class="list-group-item">
            <a href="#" title="hdfc retail loan login"> Make sure the Caps Lock is off. </a>
        </li>
        <li class="list-group-item">
            <a href="#" title="hdfc retail loan login">
                Make sure that you have an active and
                reliable internet connection.
                Sometimes Internet connections cause
                unexpected errors such as timeouts or
                packet loss. </a>
        </li>
        <li class="list-group-item">
            <a href="#" title="hdfc retail loan login"> Make sure the Caps Lock is off. </a>
        </li>
        <li class="list-group-item">
            <a href="#" title="hdfc retail loan login">
                Make sure that you have an active and
                reliable internet connection.
                Sometimes Internet connections cause
                unexpected errors such as timeouts or
                packet loss. </a>
        </li>
        <li class="list-group-item">
            <a href="#" title="hdfc retail loan login"> Make sure the Caps Lock is off. </a>
        </li>
        <li class="list-group-item">
            <a href="#" title="hdfc retail loan login">
                Make sure that you have an active and
                reliable internet connection.
                Sometimes Internet connections cause
                unexpected errors such as timeouts or
                packet loss. </a>
        </li>
    </ul>

</div>