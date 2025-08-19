<div class="p-3" style="background: #f8f9fa;">
    <div class="border rounded bg-white shadow-sm">
        <div class="p-2 text-center text-muted border-bottom" style="background: #f8f9fa; font-size: 12px; font-weight: 600;">
            <i class="fas fa-eye me-1"></i> Live Preview
        </div>

        <div style="min-height: 300px; overflow: hidden;">
            @php
                // Get data directly from the passed variables
                $section = $section_name ?? null;
                $content = $content ?? [];
                $visible = $visible ?? true;
                
                // Enhanced image URL function with better file handling
                $getImageUrl = function ($key) use ($content, $livewire) {
                    if (empty($content[$key])) {
                        return null;
                    }
                    
                    $imagePath = $content[$key];
                    
                    // Handle array format with UUID keys and saved paths
                    if (is_array($imagePath)) {
                        if (empty($imagePath)) {
                            return null;
                        }
                        
                        // Check if any value in the array is a saved file path
                        foreach ($imagePath as $uuid => $savedPath) {
                            if (is_string($savedPath) && str_starts_with($savedPath, 'about/')) {
                                // This is a saved file path - use it directly
                                return asset('storage/' . $savedPath);
                            }
                        }
                        
                        // If no saved path found, check for UUID key pattern
                        $firstKey = array_keys($imagePath)[0];
                        if (preg_match('/^[a-f0-9-]{36}$/', $firstKey)) {
                            return url('/livewire/preview-file/' . $firstKey);
                        }
                        
                        // Get first array element
                        $imagePath = $imagePath[0] ?? null;
                        if (!$imagePath) {
                            return null;
                        }
                    }
                    
                    // Handle string paths
                    if (is_string($imagePath)) {
                        // Handle saved files (about/ prefix)
                        if (str_starts_with($imagePath, 'about/')) {
                            return asset('storage/' . $imagePath);
                        }
                        // Handle UUID strings
                        if (preg_match('/^[a-f0-9-]{36}$/', $imagePath)) {
                            return url('/livewire/preview-file/' . $imagePath);
                        }
                        // Handle complete URLs
                        if (str_starts_with($imagePath, 'http')) {
                            return $imagePath;
                        }
                        // Default - assume about directory
                        return asset('storage/about/' . $imagePath);
                    }
                    
                    return null;
                };
                
                // Simplified function focusing on the actual structure we see
                $getBestImageUrl = function ($key) use ($content) {
                    if (empty($content[$key])) {
                        return null;
                    }
                    
                    $imagePath = $content[$key];
                    
                    // Handle the exact structure we see: {"uuid": "about/filename.jpg"}
                    if (is_array($imagePath)) {
                        foreach ($imagePath as $uuid => $savedPath) {
                            // If we have a saved path (about/ prefix), use it
                            if (is_string($savedPath) && str_starts_with($savedPath, 'about/')) {
                                return asset('storage/' . $savedPath);
                            }
                            // If we have just a UUID, try the preview URL
                            if (empty($savedPath) && preg_match('/^[a-f0-9-]{36}$/', $uuid)) {
                                return url('/livewire/preview-file/' . $uuid);
                            }
                        }
                    }
                    
                    // Handle direct string paths
                    if (is_string($imagePath)) {
                        if (str_starts_with($imagePath, 'about/')) {
                            return asset('storage/' . $imagePath);
                        }
                        if (preg_match('/^[a-f0-9-]{36}$/', $imagePath)) {
                            return url('/livewire/preview-file/' . $imagePath);
                        }
                    }
                    
                    return null;
                };
            @endphp

            @if(!$visible)
                {{-- Hidden Section State --}}
                <div style="padding: 40px; text-align: center; color: #999;">
                    <div style="border: 2px dashed #ddd; padding: 30px; border-radius: 8px; background: #f8f9fa;">
                        <i class="fas fa-eye-slash" style="font-size: 32px; margin-bottom: 12px; color: #ccc;"></i>
                        <h6 style="color: #666; margin: 0;">Section is Hidden</h6>
                        <small style="color: #999;">Enable visibility to show preview</small>
                    </div>
                </div>

            @elseif($section === 'hero')
                {{-- INNER PAGE HERO - Exact Match --}}
                <section class="inner-page-hero division" style="position: relative; height: 280px; background: linear-gradient(135deg, #2c3e50, #3498db); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    @php $heroImageUrl = $getBestImageUrl('background_image'); @endphp
                    @if($heroImageUrl)
                        <div style="position: absolute; inset: 0; background-image: url('{{ $heroImageUrl }}'); background-size: cover; background-position: center; z-index: 1;"></div>
                        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.4); z-index: 2;"></div>
                    @endif
                    <div style="position: relative; z-index: 3; text-align: center; padding: 20px; color: white;">
                        <h2 style="font-size: 24px; font-weight: 700; margin: 0 0 8px; color: white;">
                            {{ $content['title'] ?? '' }}
                        </h2>
                        <p style="font-size: 14px; margin: 0; opacity: 0.9; color: white;">
                            {{ $content['description'] ?? '' }}
                        </p>
                    </div>
                </section>

            @elseif($section === 'about_content')
                {{-- TEXT CONTENT (CT-03) - Exact Match --}}
                <section class="pt-8 ct-03 content-section division" style="padding: 20px; background: white;">
                    <div style="display: flex; gap: 20px; min-height: 280px;">
                        {{-- Left Column --}}
                        <div style="flex: 1; padding: 12px;">
                            <div class="txt-block left-column">
                                <div class="ct-03-txt" style="margin-bottom: 20px;">
                                    <span class="section-id" style="color: #e74c3c; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                                        {{ $content['left_section_id'] ?? 'Mind, Body and Soul' }}
                                    </span>
                                    <h2 class="h2-md" style="font-size: 18px; font-weight: 700; margin: 0 0 12px; color: #2c3e50; line-height: 1.3;">
                                        {{ $content['left_title'] ?? 'Luxury salon where you will feel unique' }}
                                    </h2>
                                    <p class="mb-5" style="font-size: 12px; color: #666; line-height: 1.5; margin: 0;">
                                        {{ $content['left_body'] ?? 'Welcome to our premium beauty sanctuary where elegance meets expertise. Our skilled beauty specialists are dedicated to delivering personalized services that enhance your natural beauty.' }}
                                    </p>
                                </div>
                                @if($getBestImageUrl('left_image'))
                                    <div class="ct-03-img">
                                        <img class="img-fluid" src="{{ $getBestImageUrl('left_image') }}" alt="content-image" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px;">
                                    </div>
                                @else
                                    <div style="width: 100%; height: 100px; background: #f8f9fa; border: 1px dashed #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 11px;">
                                        Left Image
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div style="flex: 1; padding: 12px;">
                            <div class="txt-block right-column">
                                @if($getBestImageUrl('right_image'))
                                    <div class="ct-03-img mb-5" style="margin-bottom: 20px;">
                                        <img class="img-fluid" src="{{ $getBestImageUrl('right_image') }}" alt="content-image" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px;">
                                    </div>
                                @else
                                    <div style="width: 100%; height: 100px; background: #f8f9fa; border: 1px dashed #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 11px; margin-bottom: 20px;">
                                        Right Image
                                    </div>
                                @endif
                                <div class="ct-03-txt">
                                    <p class="mb-0" style="font-size: 12px; color: #666; line-height: 1.5; margin: 0;">
                                        {{ $content['right_body'] ?? 'At mcs.sa Salon, we combine ancient beauty traditions with cutting-edge techniques to deliver exceptional results. Each service is tailored to your unique needs, using only premium products that nourish and protect.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            @elseif($section === 'center_text')
                {{-- ABOUT-1 - Exact Match --}}
                <section class="pt-8 about-1 about-section" style="padding: 32px 20px; text-align: center; background: #f8f9fa;">
                    <div class="txt-block text-center">
                        <span class="section-id" style="color: #e74c3c; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                            {{ $content['section_id'] ?? 'Indulge Yourself' }}
                        </span>
                        <h2 class="h2-title" style="font-size: 22px; font-weight: 700; margin: 0 0 16px; color: #2c3e50; line-height: 1.3;">
                            {{ $content['title_center'] ?? 'Feel Yourself More Beautiful' }}
                        </h2>
                        <p class="mb-0" style="font-size: 13px; color: #666; line-height: 1.6; margin: 0; max-width: 300px; margin-left: auto; margin-right: auto;">
                            {{ $content['body_center'] ?? 'Our salon offers a sanctuary where beauty and wellness converge. We invite you to escape the everyday and immerse yourself in luxury treatments designed to enhance your natural radiance and restore your inner balance.' }}
                        </p>
                    </div>
                </section>

            @elseif($section === 'services_preview')
                {{-- SERVICES-3 - Exact Match --}}
                <div id="services-3" class="pt-8 services-section division" style="padding: 20px; background: white;">
                    <div class="sbox-3-wrapper text-center">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 16px; max-width: 100%;">
                            @forelse($content['services'] ?? [] as $service)
                                <div class="sbox-3" style="text-align: center; padding: 20px 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #eee;">
                                    <div class="sbox-ico ico-65" style="margin-bottom: 12px;">
                                        @if(!empty($service['icon_class']))
                                            <span class="{{ $service['icon_class'] }} color--black" style="font-size: 32px; color: #2c3e50;"></span>
                                        @else
                                            <span class="flaticon-facial-treatment color--black" style="font-size: 32px; color: #2c3e50;"></span>
                                        @endif
                                    </div>
                                    <div class="sbox-txt">
                                        <p style="font-size: 11px; margin: 0; color: #333; font-weight: 500;">
                                            {{ $service['name'] ?? 'Service' }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #999;">
                                    <i class="fas fa-plus-circle" style="font-size: 32px; margin-bottom: 12px;"></i>
                                    <p style="font-size: 13px; margin: 0;">Add services to preview</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            @elseif($section === 'features_accordion')
                {{-- TEXT CONTENT CT-05 - Exact Match --}}
                <section class="pt-8 ct-05 content-section" style="padding: 20px; background: white;">
                    <div style="display: flex; gap: 20px; align-items: flex-start;">
                        <div style="flex: 1;">
                            <div class="txt-block left-column">
                                <span class="section-id" style="color: #e74c3c; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                                    {{ $content['features_section_id'] ?? 'You Are Beauty' }}
                                </span>
                                <h2 class="h2-md" style="font-size: 18px; font-weight: 700; margin: 0 0 20px; color: #2c3e50;">
                                    {{ $content['features_title'] ?? 'Give the pleasure of beautiful to yourself' }}
                                </h2>
                                
                                <div class="accordion accordion-wrapper mt-5">
                                    <ul class="accordion" style="list-style: none; padding: 0; margin: 0;">
                                        @forelse($content['features_accordion'] ?? [] as $index => $feature)
                                            <li class="accordion-item {{ $index === 0 ? 'is-active' : '' }}" style="border: 1px solid #eee; border-radius: 4px; margin-bottom: 8px; overflow: hidden;">
                                                <div class="accordion-thumb" style="background: {{ $index === 0 ? '#f8f9fa' : 'white' }}; padding: 10px 15px; cursor: pointer; border-bottom: {{ $index === 0 ? '1px solid #eee' : 'none' }};">
                                                    <p style="font-size: 12px; font-weight: 600; margin: 0; color: #333;">
                                                        {{ $feature['title'] ?? 'Feature Title' }}
                                                    </p>
                                                </div>
                                                @if($index === 0)
                                                    <div class="accordion-panel" style="padding: 10px 15px; background: white; display: block;">
                                                        <p class="mb-0" style="font-size: 11px; color: #666; margin: 0; line-height: 1.4;">
                                                            {{ $feature['content'] ?? 'Feature description content goes here...' }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </li>
                                        @empty
                                            <li style="color: #999; font-size: 12px; text-align: center; padding: 30px;">
                                                Add accordion items to preview
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div style="width: 120px;">
                            @if($getBestImageUrl('features_image'))
                                <div class="ct-05-img right-column">
                                    <img class="img-fluid" src="{{ $getBestImageUrl('features_image') }}" alt="content-image" style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px;">
                                </div>
                            @else
                                <div style="width: 100%; height: 150px; background: #f8f9fa; border: 1px dashed #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 10px;">
                                    Feature Image
                                </div>
                            @endif
                        </div>
                    </div>
                </section>

            @elseif($section === 'working_hours')
                {{-- WORKING HOURS - Exact Match --}}
                <section class="py-8 ct-table content-section division" style="padding: 20px; background: #f8f9fa;">
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <div style="flex: 1;">
                            <div class="txt-table left-column">
                                <table class="table" style="width: 100%; border-collapse: collapse;">
                                    <tbody>
                                        @forelse($content['working_hours'] ?? [] as $index => $hours)
                                            <tr class="{{ $index === count($content['working_hours'] ?? []) - 1 ? 'last-tr' : '' }}" style="border-bottom: {{ $index === count($content['working_hours'] ?? []) - 1 ? 'none' : '1px solid #f0f0f0' }};">
                                                <td style="padding: 8px 0; font-size: 12px; color: {{ $content['dayNameColor'] ?? '#333' }};">
                                                    {{ $hours['day'] ?? 'Day' }}
                                                </td>
                                                <td style="padding: 8px; font-size: 12px; color: #999;"> - </td>
                                                <td class="text-end" style="padding: 8px 0; font-size: 12px; text-align: right; color: {{ $content['timeColor'] ?? '#666' }};">
                                                    {{ $hours['time'] ?? 'Time' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" style="padding: 20px; text-align: center; color: #999; font-size: 12px;">
                                                    Add working hours
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div style="flex: 1;">
                            <div class="right-column">
                                <span class="section-id" style="color: {{ $content['smallTitleColor'] ?? '#e74c3c' }}; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                                    {{ $content['smallTitle'] ?? 'Working Hours' }}
                                </span>
                                <h2 class="h2-md" style="font-size: 18px; font-weight: 700; margin: 0 0 12px; color: {{ $content['titleColor'] ?? '#2c3e50' }};">
                                    {{ $content['title'] ?? 'Our Schedule' }}
                                </h2>
                                <p class="mb-0" style="font-size: 12px; color: {{ $content['descriptionColor'] ?? '#666' }}; line-height: 1.5; margin: 0;">
                                    {{ $content['description'] ?? 'Contact us during our business hours for appointments and inquiries.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

            @elseif($section === 'wide_image')
                {{-- WIDE IMAGE BG--01 - Exact Match --}}
                <div class="bg--01 bg--scroll ct-12 content-section" style="height: 200px; position: relative; overflow: hidden; background: #2c3e50;">
                    @if($getImageUrl('image'))
                        <div style="position: absolute; inset: 0; background-image: url('{{ $getImageUrl('image') }}'); background-size: cover; background-position: center; background-attachment: scroll;"></div>
                    @else
                        <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: white;">
                            <div style="text-align: center;">
                                <i class="fas fa-image" style="font-size: 40px; margin-bottom: 12px;"></i>
                                <p style="font-size: 16px; margin: 0;">Wide Background Image</p>
                            </div>
                        </div>
                    @endif
                </div>

            @elseif($section === 'about5')
                {{-- ABOUT-5 - Exact Match --}}
                <section class="pt-8 about-5 about-section" style="padding: 20px; background: #f8f9fa;">
                    <div style="display: flex; gap: 12px; align-items: stretch;">
                        <div style="flex: 1;">
                            @if($getImageUrl('image_1'))
                                <div id="ab-5-1" class="about-5-img">
                                    <img class="img-fluid" src="{{ $getImageUrl('image_1') }}" alt="about-image" style="width: 100%; height: 140px; object-fit: cover; border-radius: 4px;">
                                </div>
                            @else
                                <div style="width: 100%; height: 140px; background: white; border: 1px dashed #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 11px;">
                                    Image 1
                                </div>
                            @endif
                        </div>
                        
                        <div style="flex: 2; padding: 20px; background: white; border-radius: 4px; display: flex; flex-direction: column; justify-content: center;">
                            <div class="txt-block">
                                <span class="section-id" style="color: #e74c3c; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                                    {{ $content['small_title'] ?? 'Be Irresistible' }}
                                </span>
                                <h2 class="h2-title" style="font-size: 18px; font-weight: 700; margin: 0; color: #2c3e50; line-height: 1.3;">
                                    {{ $content['title'] ?? 'The Ultimate Relaxation for Your Mind and Body' }}
                                </h2>
                                @if($getImageUrl('image_2'))
                                    <div id="ab-5-2" class="about-5-img" style="margin-top: 12px;">
                                        <img class="img-fluid" src="{{ $getImageUrl('image_2') }}" alt="about-image" style="width: 100%; height: 60px; object-fit: cover; border-radius: 4px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div style="flex: 1;">
                            @if($getImageUrl('image_3'))
                                <div id="ab-5-3" class="about-5-img">
                                    <img class="img-fluid" src="{{ $getImageUrl('image_3') }}" alt="about-image" style="width: 100%; height: 140px; object-fit: cover; border-radius: 4px;">
                                </div>
                            @else
                                <div style="width: 100%; height: 140px; background: white; border: 1px dashed #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 11px;">
                                    Image 3
                                </div>
                            @endif
                        </div>
                    </div>
                </section>

            @elseif($section === 'banner_promo')
                {{-- BANNER-1 - Exact Match --}}
                <section class="pt-8 banner-1 banner-section" style="padding: 20px;">
                    <div class="banner-1-wrapper bg--fixed" style="height: 200px; position: relative; background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 6px; overflow: hidden;">
                        @if($getImageUrl('background'))
                            <div style="position: absolute; inset: 0; background-image: url('{{ $getImageUrl('background') }}'); background-size: cover; background-position: center; background-attachment: fixed;"></div>
                            <div style="position: absolute; inset: 0; background: rgba(231, 76, 60, 0.7);"></div>
                        @endif
                        <div style="position: relative; height: 100%; display: flex; align-items: center; justify-content: center;">
                            <div class="banner-1-txt text-center color--white" style="text-align: center; color: white; padding: 20px;">
                                <span class="section-id" style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; margin-bottom: 6px; display: block;">
                                    {{ $content['small_title'] ?? 'This Week Only' }}
                                </span>
                                <h2 style="font-size: 24px; font-weight: 700; margin: 0; color: white; line-height: 1.2;">
                                    {{ $content['title'] ?? 'Get 30% OFF' }}
                                </h2>
                                <h3 style="font-size: 18px; font-weight: 600; margin: 4px 0 16px; color: white; opacity: 0.95;">
                                    {{ $content['subtitle'] ?? 'Manicure + Gel Polish' }}
                                </h3>
                                @if($content['button_text'] ?? null)
                                    <a href="{{ $content['button_link'] ?? '#' }}" class="btn btn--tra-white hover--white" style="background: white; color: #e74c3c; border: 2px solid white; padding: 10px 20px; border-radius: 4px; font-size: 12px; font-weight: 600; text-decoration: none; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block;">
                                        {{ $content['button_text'] }}
                                    </a>
                                @else
                                    <div style="background: white; color: #e74c3c; border: 2px solid white; padding: 10px 20px; border-radius: 4px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block;">
                                        Book an Appointment
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

            @else
                {{-- Default/Unknown Section --}}
                <div style="padding: 40px; text-align: center; color: #666;">
                    <div style="border: 2px dashed #ddd; padding: 30px; border-radius: 8px; background: #f8f9fa;">
                        <i class="fas fa-cube" style="font-size: 32px; margin-bottom: 12px; color: #ccc;"></i>
                        <h6 style="margin: 0 0 4px; color: #666;">
                            {{ $section ? ucfirst(str_replace('_', ' ', $section)) : 'Select Section Type' }}
                        </h6>
                        <small style="color: #999;">
                            Choose a section type to see preview
                        </small>
                        @if($content['title'] ?? null)
                            <div style="margin-top: 8px; font-size: 12px; color: #999;">
                                "{{ $content['title'] }}"
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Footer Info --}}
        <div class="border-top p-2" style="background: #f8f9fa; font-size: 11px; color: #666;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong>Section:</strong> {{ $section ? ucfirst(str_replace('_', ' ', $section)) : '—' }}
                </div>
                <div>
                    <strong>Status:</strong> 
                    <span style="color: {{ $visible ? '#28a745' : '#dc3545' }};">
                        {{ $visible ? 'Visible' : 'Hidden' }}
                    </span>
                </div>
                <div>
                    <strong>Images:</strong> 
                    @php
                        $hasImages = collect(['background_image', 'left_image', 'right_image', 'features_image', 'image', 'image_1', 'image_2', 'image_3', 'background'])
                            ->some(fn($key) => $getBestImageUrl($key));
                    @endphp
                    <span style="color: {{ $hasImages ? '#28a745' : '#999' }};">
                        {{ $hasImages ? 'Uploaded' : 'None' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>