@props([
  'post',
])

@php
  /** @var \App\Models\Post $post */
  $images = $post->spotlightImageUrls();
  $videoUrl = postVideo($post->featured_video);
  $poster = postImage($post->featured_image);
  $text = $post->spotlightPresentationText();
  $story = $post->usesStoryVideo();
  $floatLabel = $post->isActivity() ? 'Activité en cours' : 'À la une';
@endphp

<div
  class="comco-spotlight"
  x-data="comcoSpotlight({
    postId: {{ (int) $post->id }},
    imageCount: {{ count($images) }},
    hasVideo: {{ $videoUrl ? 'true' : 'false' }},
    isStory: {{ $story ? 'true' : 'false' }}
  })"
  x-cloak
>
  <div
    class="comco-spotlight__overlay"
    x-show="open"
    x-transition.opacity
    @keydown.escape.window="closeModal()"
    role="dialog"
    aria-modal="true"
    aria-labelledby="comco-spotlight-title"
  >
    <div class="comco-spotlight__dialog" @click.outside="closeModal()">
      <button type="button" class="comco-spotlight__close" @click="closeModal()" aria-label="Fermer">
        <span class="fas fa-times"></span>
      </button>

      <div class="comco-spotlight__media {{ $story && $videoUrl ? 'comco-spotlight__media--story' : '' }}">
        @if ($videoUrl && $story)
          <div class="comco-spotlight__story">
            <video
              x-ref="storyVideo"
              class="comco-spotlight__story-video"
              src="{{ $videoUrl }}"
              poster="{{ $poster }}"
              playsinline
              muted
              loop
              autoplay
            ></video>
          </div>
        @elseif (count($images) > 0)
          <div class="comco-spotlight__slides">
            @foreach ($images as $index => $imageUrl)
              <img
                src="{{ $imageUrl }}"
                alt="{{ $post->title }}"
                class="comco-spotlight__slide"
                x-show="slide === {{ $index }}"
                x-transition.opacity
              >
            @endforeach
            @if (count($images) > 1)
              <button type="button" class="comco-spotlight__nav comco-spotlight__nav--prev" @click="prevSlide()" aria-label="Image précédente">
                <span class="fas fa-chevron-left"></span>
              </button>
              <button type="button" class="comco-spotlight__nav comco-spotlight__nav--next" @click="nextSlide()" aria-label="Image suivante">
                <span class="fas fa-chevron-right"></span>
              </button>
              <div class="comco-spotlight__dots" aria-hidden="true">
                @foreach ($images as $index => $imageUrl)
                  <button
                    type="button"
                    class="comco-spotlight__dot"
                    :class="{ 'is-active': slide === {{ $index }} }"
                    @click="slide = {{ $index }}"
                  ></button>
                @endforeach
              </div>
            @endif
          </div>
        @endif

        @if ($videoUrl && ! $story)
          <div class="comco-spotlight__video-wrap">
            <video
              class="comco-spotlight__video"
              controls
              playsinline
              preload="metadata"
              poster="{{ $poster }}"
            >
              <source src="{{ $videoUrl }}">
            </video>
          </div>
        @endif
      </div>

      <div class="comco-spotlight__body">
        @if ($post->isActivity())
          <span class="badge bg-warning text-primary mb-2">Activité</span>
        @else
          <span class="badge bg-primary mb-2">Actualité</span>
        @endif
        <h3 id="comco-spotlight-title" class="comco-spotlight__title">{{ $post->title }}</h3>
        @if ($text !== '')
          <p class="comco-spotlight__text">{{ $text }}</p>
        @endif
        <a class="btn btn-primary" href="{{ route('posts.show', $post->slug) }}">
          Lire la suite
        </a>
      </div>
    </div>
  </div>

  <button
    type="button"
    class="comco-spotlight__float"
    x-show="!open"
    x-transition.opacity
    @click="openModal()"
    :aria-label="'Rouvrir : {{ $floatLabel }}'"
  >
    <span class="comco-spotlight__float-pulse" aria-hidden="true"></span>
    <span class="comco-spotlight__float-icon fas fa-bullhorn" aria-hidden="true"></span>
    <span class="comco-spotlight__float-label">{{ $floatLabel }}</span>
  </button>
</div>

<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('comcoSpotlight', (config) => ({
      open: false,
      slide: 0,
      timer: null,
      storageKey: 'comco_spotlight_dismissed_' + config.postId,
      init() {
        const dismissed = window.localStorage.getItem(this.storageKey) === '1';
        this.open = !dismissed;
        if (this.open) {
          this.startAutoplay();
          this.$nextTick(() => this.playStory());
        }
      },
      openModal() {
        this.open = true;
        this.startAutoplay();
        this.$nextTick(() => this.playStory());
      },
      closeModal() {
        this.open = false;
        window.localStorage.setItem(this.storageKey, '1');
        this.stopAutoplay();
        this.pauseStory();
      },
      nextSlide() {
        if (config.imageCount < 2) {
          return;
        }
        this.slide = (this.slide + 1) % config.imageCount;
      },
      prevSlide() {
        if (config.imageCount < 2) {
          return;
        }
        this.slide = (this.slide - 1 + config.imageCount) % config.imageCount;
      },
      startAutoplay() {
        this.stopAutoplay();
        if (config.imageCount < 2 || (config.hasVideo && config.isStory)) {
          return;
        }
        this.timer = window.setInterval(() => this.nextSlide(), 4500);
      },
      stopAutoplay() {
        if (this.timer) {
          window.clearInterval(this.timer);
          this.timer = null;
        }
      },
      playStory() {
        if (!config.hasVideo || !config.isStory || !this.$refs.storyVideo) {
          return;
        }
        const video = this.$refs.storyVideo;
        video.muted = true;
        const playPromise = video.play();
        if (playPromise && typeof playPromise.catch === 'function') {
          playPromise.catch(() => {});
        }
      },
      pauseStory() {
        if (!this.$refs.storyVideo) {
          return;
        }
        this.$refs.storyVideo.pause();
      },
    }));
  });
</script>
