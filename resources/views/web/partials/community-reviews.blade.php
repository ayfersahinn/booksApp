 <div id="communityReviews">
     @foreach($reviews as $review)
     <!-- Yorum Kartı  -->
     <div id="review-{{ $review->id }}" class="card border-0 shadow-sm review-card p-3">
         <div class="d-flex gap-3">
             <img src="https://via.placeholder.com/45" class="rounded-circle avatar" alt="Kullanıcı">
             <div class="w-100">
                 <div class="d-flex justify-content-between align-items-start mb-1">
                     <div>
                         <h6 class="fw-bold mb-0">{{$review->user->name}} </h6>
                         <small class="text-muted d-block mb-2">
                             {{ $review->review_updated_at?->diffForHumans() }}
                         </small>
                     </div>
                     <div class="rating-stars small">
                         @for($i = 1; $i <= 5; $i++)
                             <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                             @endfor
                     </div>
                 </div>

                 <div class="bg-light p-2 rounded d-flex align-items-center gap-3 my-2">
                     <img src="{{ asset('storage/' . $review->book->cover_image) }}"
                         class="book-cover-thumb shadow-sm"
                         alt="{{ $review->book->title }}">
                     <div>
                         <a href="{{route('book-detail' , [$review->book->slug, $review->book->isbn])}}" class="fw-bold text-dark text-decoration-none d-block">{{$review->book->title}}</a>
                         <small class="text-muted d-block">Yazar: {{$review->book->author}}</small>
                         <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">{{$review->book->category->name}}</span>
                     </div>
                 </div>

                 <!-- Spoiler Uyarısı -->
                 @if($review->has_spoiler)
                 <div class="alert alert-warning py-2 px-3 small d-flex align-items-center gap-2 mb-2">
                     <i class="bi bi-exclamation-triangle-fill"></i>
                     <span>Bu yorum <strong>spoiler</strong> içermektedir.</span>
                 </div>
                 @endif

                 <p class="card-text  mb-3">
                     {{$review->review}}
                 </p>

                 <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                     <div class="d-flex gap-2">
                         @if($review->user_id != Auth::id())

                         @php
                         $isHelpful = $review->helpfuls->contains('user_id', Auth::id());
                         @endphp

                         <form action="{{ route('toggleHelpful', $review->id) }}" method="post" class="helpful-form">
                             @csrf

                             <button type="submit"
                                 class="btn btn-sm {{ $isHelpful ? 'btn-success' : 'btn-outline-secondary' }}">
                                 <i class="bi bi-hand-thumbs-up me-1"></i>
                                 Faydalı {{ $review->helpfuls->count() }}
                             </button>
                         </form>

                         @endif
                         <button type="submit" class="btn btn-sm btn-outline-secondary reply-toggle">
                             <i class="bi bi-chat me-1"></i> Yanıtla {{$review->comments->count()}}
                         </button>
                     </div>
                     <button class="btn btn-sm text-muted p-0" title="Bildir">
                         <i class="bi bi-flag"></i>
                     </button>
                 </div>
                 {{-- Yanıt Alanı --}}
                 <div class="reply-section mt-3 pt-3 border-top d-none">
                     @if($review->comments->count() > 0)

                     <div class="comments-list d-none mt-3">

                         @foreach($review->comments as $comment)

                         <div class="border-top pt-2 mb-2">
                             <strong>{{ $comment->user->name }}</strong>
                             <div class="d-flex justify-content-between">
                                 <p class="mb-0 text-secondary">
                                     {{ $comment->content }}
                                 </p>
                                 @if($comment->user_id==Auth::id())
                                 <form action="{{route('delete-comment', $comment->id)}}" method="post" class="delete-comment-form">
                                     @csrf
                                     @method('DELETE')
                                     <button type="submit" class="btn btn-sm btn-outline-danger">
                                         Sil
                                     </button>
                                 </form>
                                 @endif
                             </div>
                         </div>

                         @endforeach

                     </div>

                     @endif
                     <form action="{{ route('replyToReview', $review->id) }}" method="POST" class="reply-form-submit">
                         @csrf
                         <div class="mb-2">
                             <textarea name="content" class="form-control" rows="3" placeholder="Yanıtınızı yazın..." required></textarea>
                         </div>
                         <div class="d-flex justify-content-end">
                             <button type="submit" class="btn btn-primary btn-sm "> <i class="bi bi-send me-1"></i> Yanıtla </button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>

     @endforeach

     <!-- Sayfalandırma (Pagination) -->

     <div class="mt-4">
         {{ $reviews->links() }}
     </div>

 </div>