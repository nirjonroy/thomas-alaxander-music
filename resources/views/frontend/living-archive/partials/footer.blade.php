<footer class="archive-footer">
    <div class="archive-footer__inner">
        <p>&copy; Copyright {{ now()->year }} Thomas Alexander. All Rights Reserved</p>
        <div class="archive-footer__links">
            <a href="{{ route('front.home.living-archive') }}">Living Archive</a>
            @if ($showSupport ?? true)
                <a href="{{ route('living-archive.donate') }}">Offer Support</a>
            @endif
        </div>
    </div>
</footer>
