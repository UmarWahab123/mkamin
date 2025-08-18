
    <div class="team-members-category container mt-6">
        <!-- CATEGORY TITLE -->
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center text-center mb-6">
                    <!-- Section ID -->
                    <span class="section-id text-center" style="color:{{ $section->content['small_title_color'] ?? '#af8855' }}">{{ isset($section->content['section_id']) ? __($section->content['section_id']) : __('Best Selling Services') }}</span>
                    <!-- Title -->
                    <h2 class="h2-title" style="color: {{ $section->content['title_color'] ?? '#363636' }}">{{ isset($section->content['title']) ? __($section->content['title']) : __('Trending Services') }}</h2>
                </div>
            </div>
        </div>
        <!-- TEAM MEMBERS WRAPPER -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">
            @foreach ($services as $service)
                <!-- TEAM MEMBER #1 -->
                <div class="col">
                    <div class="team-member wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                        <!-- Team Member Photo -->
                        <div class="team-member-photo">
                            <div class="hover-overlay" style="height: 300px; overflow: hidden;">
                                <a href="{{ route('services.detail', $service->id) }}">
                                    <img class="img-fluid rounded w-100 h-100" src="{{ asset('storage/' . $service->image) }}" alt="team-member-foto" style="object-fit: cover; object-position: center;">
                                    <div class="item-overlay"></div>
                                </a>
                            </div>
                        </div>
                        <!-- Team Member Data -->
                        <div class="team-member-data">
                            <!-- Title -->
                            <span class="section-id">{{ $service->name }}</span>
                            {{-- <h5 class="h5-lg">{{ $service->name }}</h5> --}}
                            <!-- Link -->
                            <p class="tra-link"><a href="#">{{__('View More')}}</a></p>
                        </div>
                    </div>
                </div>
                <!-- END TEAM MEMBER #1 -->
            @endforeach
        </div>
        <!-- END TEAM MEMBERS WRAPPER -->



    </div>

