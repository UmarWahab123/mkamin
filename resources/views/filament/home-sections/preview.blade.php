<div class="p-3" style="background: #f8f9fa;">
    <div class="border rounded bg-white shadow-sm">
        <div class="p-2 text-center text-muted border-bottom" style="background: #f8f9fa; font-size: 12px; font-weight: 600;">
            <i class="fas fa-home me-1"></i> Home Page Preview
        </div>

        <div style="min-height: 300px; overflow: hidden;">
            @php
                // Get data directly from the passed variables
                $section = $section_name ?? null;
                $content = $content ?? [];
                $visible = $visible ?? true;
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
            {{-- TEMPORARY DEBUG VERSION - Use this to see what data is being passed --}}

           @elseif($section === 'hero_section')
            {{-- HERO SECTION - LIVE PREVIEW WORKING PERFECTLY --}}
            <section id="hero-12" class="hero-section" style="position: relative; height: 280px; background: linear-gradient(135deg, #af8855, #8b6f42); overflow: hidden;">
                @php
                    $slides = $content['slides'] ?? [];
                    $slide = null;
                    
                    if (!empty($slides) && is_array($slides)) {
                        $slide = reset($slides); // Get first slide
                    }
                @endphp
                
                @if($slide)
                    {{-- Background Image --}}
                    @if(isset($slide['bgImage']) && $slide['bgImage'])
                        @php
                            $bgImageUrl = null;
                            if (is_array($slide['bgImage'])) {
                                foreach ($slide['bgImage'] as $uuid => $savedPath) {
                                    if (is_string($savedPath) && str_starts_with($savedPath, 'home/')) {
                                        $bgImageUrl = asset('storage/' . $savedPath);
                                        break;
                                    }
                                    if (empty($savedPath) && preg_match('/^[a-f0-9-]{36}$/', $uuid)) {
                                        $bgImageUrl = url('/livewire/preview-file/' . $uuid);
                                        break;
                                    }
                                }
                            } elseif (is_string($slide['bgImage'])) {
                                if (str_starts_with($slide['bgImage'], 'home/')) {
                                    $bgImageUrl = asset('storage/' . $slide['bgImage']);
                                } elseif (preg_match('/^[a-f0-9-]{36}$/', $slide['bgImage'])) {
                                    $bgImageUrl = url('/livewire/preview-file/' . $slide['bgImage']);
                                }
                            }
                        @endphp
                        
                        @if($bgImageUrl)
                            <div style="position: absolute; inset: 0; background-image: url('{{ $bgImageUrl }}'); background-size: cover; background-position: center; z-index: 1;"></div>
                            <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.4); z-index: 2;"></div>
                        @endif
                    @endif
                    
                    {{-- Content --}}
                    <div style="position: relative; z-index: 3; height: 100%; display: flex; align-items: center; justify-content: center; text-align: center; padding: 20px;">
                        <div class="caption">
                            {{-- Small Title --}}
                            @if(!empty($slide['smallTitle']))
                                <span class="slide-small-title" style="color: {{ $slide['smallTitleColor'] ?? '#af8855' }}; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                                    {{ $slide['smallTitle'] }}
                                </span>
                            @endif
                            
                            {{-- Main Title --}}
                            <div class="title">
                                <h2 class="slide-title" style="font-size: 28px; font-weight: 700; margin: 0; color: {{ $slide['titleColor'] ?? '#ffffff' }}; line-height: 1.2;">
                                    {{ $slide['title'] ?? 'Welcome to mcs.sa' }}
                                </h2>
                            </div>
                            
                            {{-- Button --}}
                            @if(!empty($slide['buttonText']))
                                <div class="text" style="margin-top: 20px;">
                                    <a href="{{ $slide['buttonUrl'] ?? '#' }}" class="custom-btn" style="background-color: {{ $slide['buttonBgColor'] ?? '#af8855' }}; color: {{ $slide['buttonTextColor'] ?? '#ffffff' }}; padding: 12px 24px; border-radius: 4px; font-size: 12px; font-weight: 600; text-decoration: none; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; border: 2px solid {{ $slide['buttonBgColor'] ?? '#af8855' }};">
                                        {{ $slide['buttonText'] }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- Empty State --}}
                    <div style="position: relative; z-index: 3; height: 100%; display: flex; align-items: center; justify-content: center; text-align: center; padding: 20px; color: white;">
                        <div>
                            <i class="fas fa-plus-circle" style="font-size: 32px; margin-bottom: 12px; opacity: 0.7;"></i>
                            <h6 style="margin: 0; opacity: 0.9;">Add Hero Slides</h6>
                            <small style="opacity: 0.7;">Create slides to preview hero section</small>
                        </div>
                    </div>
                @endif
            </section>
            @elseif($section === 'trending_services')
                {{-- TRENDING SERVICES - Matches home.blade.php structure --}}
                <div class="team-members-category container" style="padding: 20px; background: white;">
                    <div class="section-title text-center mb-6" style="text-align: center; margin-bottom: 20px;">
                        <span class="section-id" style="color: {{ $content['small_title_color'] ?? '#af8855' }}; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                            {{ $content['section_id'] ?? 'Best Selling Services' }}
                        </span>
                        <h2 class="h2-title" style="font-size: 20px; font-weight: 700; margin: 0; color: {{ $content['title_color'] ?? '#363636' }};">
                            {{ $content['title'] ?? 'Trending Services' }}
                        </h2>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 16px;">
                        @for($i = 0; $i < 4; $i++)
                            <div class="team-member" style="text-align: center; background: #f8f9fa; border-radius: 6px; overflow: hidden;">
                                <div class="team-member-photo" style="height: 100px; background: #e9ecef; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                    <div class="hover-overlay" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image" style="font-size: 24px; color: #ccc;"></i>
                                    </div>
                                </div>
                                <div class="team-member-data" style="padding: 12px;">
                                    <span class="section-id" style="font-size: 11px; font-weight: 500; color: #333;">Service {{ $i + 1 }}</span>
                                    <p class="tra-link" style="font-size: 10px; margin: 4px 0 0; color: #666;">View More</p>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

            @elseif($section === 'text_content_1')
                {{-- TEXT CONTENT 1 - Matches text-content-1.blade.php --}}
                <section class="pt-8 ct-01 content-section division" style="padding: 20px; background: white;">
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <div style="flex: 1;">
                            <div class="txt-block left-column wow fadeInRight">
                                <span class="section-id color--gold" style="color: {{ $content['smallTitleColor'] ?? '#af8855' }}; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                                    {{ $content['smallTitle'] ?? 'Welcome' }}
                                </span>
                                <h2 class="h2-md" style="font-size: 20px; font-weight: 700; margin: 0 0 12px; color: {{ $content['titleColor'] ?? '#363636' }}; line-height: 1.3;">
                                    {{ $content['title'] ?? 'Experience Luxury Beauty' }}
                                </h2>
                                <p class="mb-0" style="font-size: 12px; color: {{ $content['descriptionColor'] ?? '#666' }}; line-height: 1.5; margin: 0;">
                                    {{ $content['description'] ?? 'Discover our exceptional beauty services designed to enhance your natural elegance and provide you with the ultimate relaxation experience.' }}
                                </p>
                            </div>
                        </div>
                        <div style="width: 140px;">
                            <div class="d-flex justify-content-center">
                                @if($getBestImageUrl('image'))
                                    <img class="img-fluid rounded w-75 mx-auto" src="{{ $getBestImageUrl('image') }}" alt="content-image" style="width: 100%; height: 120px; object-fit: cover; border-radius: 6px;">
                                @else
                                    <div style="width: 100%; height: 120px; background: #f8f9fa; border: 1px dashed #ddd; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 11px;">
                                        Content Image
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

            @elseif($section === 'text_content_2')
                {{-- TEXT CONTENT 2 - Matches text-content-2.blade.php --}}
                <section class="pt-8 about-6 about-section" style="padding: 20px; background: #f8f9fa;">
                    <div class="section-title text-center mb-6" style="text-align: center; margin-bottom: 20px;">
                        <span class="section-id" style="color: {{ $content['smallTitleColor'] ?? '#af8855' }}; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                            {{ $content['smallTitle'] ?? 'About Us' }}
                        </span>
                        <h2 class="h2-title" style="font-size: 20px; font-weight: 700; margin: 0; color: {{ $content['titleColor'] ?? '#363636' }};">
                            {{ $content['title'] ?? 'Our Story' }}
                        </h2>
                    </div>
                    
                    <div style="display: flex; gap: 12px; align-items: stretch;">
                        <!-- IMAGE BLOCK -->
                        <div style="flex: 1;">
                            <div id="a6-img-1" class="about-6-img">
                                @if($getBestImageUrl('image1'))
                                    <img class="img-fluid" src="{{ $getBestImageUrl('image1') }}" alt="about-image" style="width: 100%; height: 120px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <div style="width: 100%; height: 120px; background: white; border: 1px dashed #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 11px;">
                                        Image 1
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- TEXT BLOCK -->
                        <div style="flex: 1;">
                            <div class="about-6-txt">
                                <div class="a6-txt" style="background-color: {{ $content['cardBackgroundColor'] ?? '#ffffff' }}; padding: 16px; border-radius: 4px; margin-bottom: 12px;">
                                    <h4 class="h4-md" style="font-size: 14px; font-weight: 600; margin: 0 0 8px; color: {{ $content['cardTitleColor'] ?? '#363636' }};">
                                        {{ $content['cardTitle'] ?? 'Professional Care' }}
                                    </h4>
                                    <p class="mb-0" style="font-size: 11px; color: {{ $content['cardDescriptionColor'] ?? '#666' }}; margin: 0; line-height: 1.4;">
                                        {{ $content['cardDescription'] ?? 'Expert stylists with years of experience in beauty and wellness.' }}
                                    </p>
                                </div>
                                <div class="a6-img">
                                    @if($getBestImageUrl('image3'))
                                        <img class="img-fluid" src="{{ $getBestImageUrl('image3') }}" alt="about-image" style="width: 100%; height: 60px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <div style="width: 100%; height: 60px; background: white; border: 1px dashed #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 10px;">
                                            Image 3
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- IMAGE BLOCK -->
                        <div style="flex: 1;">
                            <div id="a6-img-2" class="about-6-img">
                                @if($getBestImageUrl('image2'))
                                    <img class="img-fluid" src="{{ $getBestImageUrl('image2') }}" alt="about-image" style="width: 100%; height: 120px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <div style="width: 100%; height: 120px; background: white; border: 1px dashed #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 11px;">
                                        Image 2
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

            @elseif($section === 'services_section')
                {{-- SERVICES SECTION - Matches services-section.blade.php --}}
                <section id="services-2" class="pt-8 services-section division" style="padding: 20px; background: white;">
                    <div class="sbox-2-wrapper text-center">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 16px;">
                            @forelse($content['services'] ?? [] as $index => $service)
                                @php
                                    // Fix: Ensure index is numeric
                                    $numericIndex = is_numeric($index) ? (int)$index : 0;
                                    $serviceClass = 'sb-' . ($numericIndex + 1);
                                    
                                    // Fix: Safely handle image URL
                                    $serviceImageUrl = null;
                                    if (isset($service['image']) && $service['image']) {
                                        try {
                                            $serviceImageUrl = $getNestedImageUrl('services.' . $numericIndex . '.image');
                                        } catch (Exception $e) {
                                            $serviceImageUrl = null;
                                        }
                                    }
                                @endphp
                                
                                <div class="sbox-2 {{ $serviceClass }} wow fadeInUp" style="text-align: center; padding: 20px 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #eee;">
                                    <div class="sbox-ico ico-65" style="margin-bottom: 12px;">
                                        @if($serviceImageUrl)
                                            <img src="{{ $serviceImageUrl }}" alt="{{ $service['title'] ?? 'Service' }}" class="service-img" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        @elseif(!empty($service['icon']))
                                            <span class="{{ $service['icon'] }} color--gold" style="font-size: 32px; color: #af8855;"></span>
                                        @else
                                            <span class="fas fa-spa color--gold" style="font-size: 32px; color: #af8855;"></span>
                                        @endif
                                    </div>
                                    <div class="sbox-txt">
                                        <h5 class="h5-lg" style="font-size: 12px; font-weight: 600; margin: 0 0 4px; color: {{ $content['titleColor'] ?? '#363636' }};">
                                            {{ $service['title'] ?? 'Service Title' }}
                                        </h5>
                                        <p style="font-size: 10px; color: {{ $content['descriptionColor'] ?? '#666' }}; margin: 0; line-height: 1.3;">
                                            @php
                                                // Fix: Safely limit string length
                                                $description = $service['description'] ?? 'Service description';
                                                $limitedDescription = strlen($description) > 50 ? substr($description, 0, 47) . '...' : $description;
                                            @endphp
                                            {{ $limitedDescription }}
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
                </section>

            @elseif($section === 'text_content_3')
                {{-- TEXT CONTENT 3 - Matches text-content-3.blade.php --}}
                <section class="pt-8 ct-07 ws-wrapper content-section" style="padding: 20px; background: #f8f9fa;">
                    <div class="ct-07-wrapper bg--black block-shadow color--white" style="background: #2c3e50; border-radius: 6px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden;">
                        <div style="display: flex; gap: 20px; align-items: center; min-height: 200px;">
                            <div style="flex: 1; padding: 20px;">
                                <div class="txt-block left-column">
                                    <span class="section-id" style="color: {{ $content['smallTitleColor'] ?? '#af8855' }}; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                                        {{ $content['smallTitle'] ?? 'Special Offer' }}
                                    </span>
                                    <h2 class="h2-md" style="font-size: 20px; font-weight: 700; margin: 0 0 12px; color: {{ $content['titleColor'] ?? '#ffffff' }}; line-height: 1.3;">
                                        {{ $content['title'] ?? 'Exclusive Beauty Package' }}
                                    </h2>
                                    <p class="mb-0" style="font-size: 12px; color: {{ $content['descriptionColor'] ?? '#ffffff' }}; line-height: 1.5; margin: 0 0 20px; opacity: 0.9;">
                                        {{ $content['description'] ?? 'Transform yourself with our comprehensive beauty treatments designed for your complete wellness and relaxation.' }}
                                    </p>
                                    @if($content['buttonText'] ?? null)
                                        <div class="txt-block-btn">
                                            <a href="{{ $content['buttonUrl'] ?? '#' }}" class="btn custom-btn" style="background-color: {{ $content['buttonBgColor'] ?? '#af8855' }}; color: {{ $content['buttonTextColor'] ?? '#ffffff' }}; border-color: {{ $content['buttonBgColor'] ?? '#af8855' }}; padding: 10px 20px; border-radius: 4px; font-size: 12px; font-weight: 600; text-decoration: none; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; border: 2px solid;">
                                                {{ $content['buttonText'] }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div style="width: 140px; padding: 20px;">
                                <div class="img-block right-column d-flex justify-content-end">
                                    @if($getBestImageUrl('image'))
                                        <img class="img-fluid" src="{{ $getBestImageUrl('image') }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 4px;" alt="content-image">
                                    @else
                                        <div style="width: 100%; height: 120px; background: #34495e; border: 1px dashed #5a6c7d; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #bbb; font-size: 11px;">
                                            Content Image
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            @elseif($section === 'wide_image_section')
                {{-- WIDE IMAGE SECTION - Matches wide-image-section.blade.php --}}
                <div class="bg--scroll ct-12 content-section" style="height: 200px; position: relative; overflow: hidden; background: #2c3e50;">
                    @if($getBestImageUrl('image'))
                        <div style="position: absolute; inset: 0; background-image: url('{{ $getBestImageUrl('image') }}'); background-size: cover; background-position: center; background-attachment: scroll;"></div>
                    @else
                        <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: white;">
                            <div style="text-align: center;">
                                <i class="fas fa-image" style="font-size: 40px; margin-bottom: 12px;"></i>
                                <p style="font-size: 16px; margin: 0;">Wide Background Image</p>
                            </div>
                        </div>
                    @endif
                </div>

            @elseif($section === 'text_content_4')
                {{-- TEXT CONTENT 4 - Matches text-content-4.blade.php --}}
                <section class="pt-8 ct-05 content-section" style="padding: 20px; background: {{ $content['backgroundColor'] ?? '#ffffff' }};">
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <div style="flex: 1;">
                            <div class="txt-block left-column wow fadeInRight">
                                <span class="section-id" style="color: {{ $content['smallTitleColor'] ?? '#af8855' }}; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                                    {{ $content['smallTitle'] ?? 'Premium Care' }}
                                </span>
                                <h2 class="h2-md" style="font-size: 18px; font-weight: 700; margin: 0 0 12px; color: {{ $content['titleColor'] ?? '#363636' }}; line-height: 1.3;">
                                    {{ $content['title'] ?? 'Professional Beauty Services' }}
                                </h2>
                                <p style="font-size: 12px; color: {{ $content['description1Color'] ?? '#666' }}; line-height: 1.5; margin: 0 0 8px;">
                                    {{ $content['description1'] ?? 'Experience the finest in beauty and wellness with our expert team of professionals.' }}
                                </p>
                                <p class="mb-0" style="font-size: 12px; color: {{ $content['description2Color'] ?? '#666' }}; line-height: 1.5; margin: 0;">
                                    {{ $content['description2'] ?? 'We use only premium products and techniques to ensure exceptional results every time.' }}
                                </p>
                            </div>
                        </div>
                        <div style="width: 140px; padding: 0;">
                            <div class="ct-05-img right-column wow fadeInLeft right-column-image d-flex justify-content-end">
                                @if($getBestImageUrl('image'))
                                    <img class="img-fluid column-image" src="{{ $getBestImageUrl('image') }}" alt="content-image" style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <div style="width: 100%; height: 150px; background: #f8f9fa; border: 1px dashed #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 11px;">
                                        Side Image
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

            @elseif($section === 'working_hours_section')
                {{-- WORKING HOURS - Matches working-hours-section.blade.php --}}
                <section class="py-8 ct-table content-section division" style="padding: 20px; background: #f8f9fa;">
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <div style="flex: 1;">
                            <div class="txt-table left-column wow fadeInRight">
                                <table class="table" style="width: 100%; border-collapse: collapse;">
                                    <tbody>
                                        @if(isset($content['workingHours']) && is_array($content['workingHours']) && count($content['workingHours']) > 0)
                                            @foreach($content['workingHours'] as $index => $hours)
                                                <tr @if($index == count($content['workingHours']) - 1) class="last-tr" @endif style="border-bottom: {{ $index === count($content['workingHours']) - 1 ? 'none' : '1px solid #f0f0f0' }};">
                                                    <td style="padding: 6px 0; font-size: 11px; color: {{ $content['dayNameColor'] ?? '#333' }};">
                                                        {{ $hours['day'] ?? 'Day' }}
                                                    </td>
                                                    <td style="padding: 6px; font-size: 11px; color: #999;"> - </td>
                                                    <td class="text-end" style="padding: 6px 0; font-size: 11px; text-align: right; color: {{ $content['timeColor'] ?? '#666' }};">
                                                        {{ $hours['time'] ?? 'Time' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            @php
                                                $sampleHours = [
                                                    ['day' => 'Monday', 'time' => '9:00 AM - 8:00 PM'],
                                                    ['day' => 'Tuesday', 'time' => '9:00 AM - 8:00 PM'],
                                                    ['day' => 'Wednesday', 'time' => '9:00 AM - 8:00 PM'],
                                                    ['day' => 'Thursday', 'time' => '9:00 AM - 8:00 PM'],
                                                    ['day' => 'Friday', 'time' => '9:00 AM - 8:00 PM'],
                                                    ['day' => 'Saturday', 'time' => '10:00 AM - 6:00 PM'],
                                                    ['day' => 'Sunday', 'time' => 'Closed'],
                                                ];
                                            @endphp
                                            @foreach($sampleHours as $index => $hours)
                                                <tr @if($index == count($sampleHours) - 1) class="last-tr" @endif style="border-bottom: {{ $index === count($sampleHours) - 1 ? 'none' : '1px solid #f0f0f0' }};">
                                                    <td style="padding: 6px 0; font-size: 11px; color: {{ $content['dayNameColor'] ?? '#333' }};">
                                                        {{ $hours['day'] }}
                                                    </td>
                                                    <td style="padding: 6px; font-size: 11px; color: #999;"> - </td>
                                                    <td class="text-end" style="padding: 6px 0; font-size: 11px; text-align: right; color: {{ $content['timeColor'] ?? '#666' }};">
                                                        {{ $hours['time'] }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div style="flex: 1;">
                            <div class="right-column wow fadeInLeft">
                                <span class="section-id" style="color: {{ $content['smallTitleColor'] ?? '#e74c3c' }}; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                                    {{ $content['smallTitle'] ?? 'Working Hours' }}
                                </span>
                                <h2 class="h2-md" style="font-size: 18px; font-weight: 700; margin: 0 0 12px; color: {{ $content['titleColor'] ?? '#2c3e50' }};">
                                    {{ $content['title'] ?? 'Visit Us Today' }}
                                </h2>
                                <p class="mb-0" style="font-size: 12px; color: {{ $content['descriptionColor'] ?? '#666' }}; line-height: 1.5; margin: 0;">
                                    {{ $content['description'] ?? 'We\'re ready to help you look and feel your best. Contact us during our business hours.' }}
                                </p>
                                
                                <div class="row justify-content-center mt-2" style="margin-top: 16px; text-align: center;">
                                    <div class="col-auto">
                                        <div class="qr-code-container text-center">
                                            <div class="card p-3 shadow-sm" style="background: white; padding: 12px; border-radius: 4px; border: 1px solid #eee; display: inline-block;">
                                                <i class="fas fa-qrcode" style="font-size: 24px; color: #af8855;"></i>
                                                <h6 class="mt-2" style="font-size: 10px; color: #666; margin-top: 4px;">Book Now</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            @elseif($section === 'contact_section')
                {{-- CONTACT SECTION - Matches contact-section.blade.php --}}
                <section id="contacts-2" class="py-8 contacts-section division" style="padding: 20px; background-color: {{ $content['backgroundColor'] ?? '#f8f9fa' }};">
                    <div style="display: flex; gap: 20px; align-items: stretch; color: {{ $content['textColor'] ?? '#333' }};">
                        <!-- HOURS & LOCATION -->
                        <div style="flex: 1;">
                            <!-- WORKING HOURS -->
                            <div class="cbox-2 cb-1 mb-5" style="margin-bottom: 20px;">
                                <h4 style="color: {{ $content['textColor'] ?? '#333' }}; font-size: 14px; font-weight: 600; margin: 0 0 12px;">
                                    {{ $content['hoursTitle'] ?? 'Working Hours' }}
                                </h4>
                                @php
                                    $sampleHours = [
                                        ['day' => 'Monday', 'time' => '9:00 AM - 8:00 PM'],
                                        ['day' => 'Tuesday', 'time' => '9:00 AM - 8:00 PM'],
                                        ['day' => 'Wednesday', 'time' => '9:00 AM - 8:00 PM'],
                                        ['day' => 'Thursday', 'time' => '9:00 AM - 8:00 PM'],
                                        ['day' => 'Friday', 'time' => '9:00 AM - 8:00 PM'],
                                    ];
                                @endphp
                                @foreach($sampleHours as $hours)
                                    <p style="font-size: 11px; margin: 4px 0; color: {{ $content['textColor'] ?? '#333' }};">
                                        <span style="display: inline-block; width: 60px;">{{ $hours['day'] }} </span>
                                        <span>{{ $hours['time'] }}</span>
                                    </p>
                                @endforeach
                            </div>

                            <!-- LOCATION -->
                            <div class="cbox-2 cb-2">
                                <h4 style="color: {{ $content['textColor'] ?? '#333' }}; font-size: 14px; font-weight: 600; margin: 0 0 12px;">
                                    {{ $content['locationTitle'] ?? 'Our Location' }}
                                </h4>
                                <p style="color: {{ $content['textColor'] ?? '#333' }}; font-size: 11px; margin: 4px 0;">
                                    {{ $content['locationAr'] ?? 'الرياض، المملكة العربية السعودية' }}
                                </p>
                                <p style="color: {{ $content['textColor'] ?? '#333' }}; font-size: 11px; margin: 4px 0;">
                                    {{ $content['locationEn'] ?? 'Riyadh, Saudi Arabia' }}
                                </p>
                                <div class="cbox-2-contacts" style="margin-top: 12px;">
                                    @if($content['phoneNo1'] ?? null)
                                        <p style="font-size: 11px; margin: 2px 0;">
                                            <a href="tel:{{ $content['phoneNo1'] }}" style="color: {{ $content['textColor'] ?? '#333' }}; text-decoration: none;">
                                                {{ $content['phoneNo1'] }}
                                            </a>
                                        </p>
                                    @endif
                                    @if($content['phoneNo2'] ?? null)
                                        <p style="font-size: 11px; margin: 2px 0;">
                                            <a href="tel:{{ $content['phoneNo2'] }}" style="color: {{ $content['textColor'] ?? '#333' }}; text-decoration: none;">
                                                {{ $content['phoneNo2'] }}
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- GOOGLE MAP -->
                        <div style="flex: 2;">
                            <div class="google-map" style="height: 180px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd;">
                                @if($content['mapSrc'] ?? null)
                                    <iframe src="{{ $content['mapSrc'] }}" width="100%" height="100%" style="border:0; border-radius: 4px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                @else
                                    <div style="text-align: center; color: #666;">
                                        <i class="fas fa-map-marker-alt" style="font-size: 32px; margin-bottom: 8px;"></i>
                                        <p style="font-size: 12px; margin: 0;">Google Map</p>
                                        <small style="font-size: 10px; color: #999;">Add map embed URL</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

            @elseif($section === 'pricing_section')
                {{-- PRICING SECTION - Matches pricing-section.blade.php --}}
                <div class="pt-8 pricing-5 pricing-section division" style="padding: 20px; background: white;">
                    @if($content['showSectionTitle'] ?? true)
                        <div class="section-title text-center mb-6" style="text-align: center; margin-bottom: 20px;">
                            <span class="section-id" style="color: #af8855; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">
                                You'll Like It Here!
                            </span>
                            <h2 class="h2-title" style="font-size: 20px; font-weight: 700; margin: 0; color: #363636;">
                                {{ $content['sectionTitle'] ?? 'Our Services & Prices' }}
                            </h2>
                        </div>
                    @endif

                    <!-- SERVICE TYPE BUTTONS -->
                    <div class="row justify-content-center mb-5" style="text-align: center; margin-bottom: 20px;">
                        <div class="col-lg-6 text-center">
                            <div class="btn-group service-type-buttons" role="group" style="display: inline-flex; border-radius: 4px; overflow: hidden; border: 1px solid #333;">
                                <button type="button" class="btn btn-lg service-type-btn active" style="padding: 8px 16px; background-color: #333; color: white; border: none; font-size: 11px;">
                                    Site Services Menu
                                </button>
                                <button type="button" class="btn btn-lg service-type-btn" style="padding: 8px 16px; background-color: white; color: #333; border: none; font-size: 11px;">
                                    Home Services Menu
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- PRICING-5 WRAPPER -->
                    <div class="pricing-5-wrapper">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            @for($i = 0; $i < 2; $i++)
                                <div class="pricing-5-table" style="background: #f8f9fa; padding: 16px; border-radius: 6px;">
                                    <!-- PRICING LIST CATEGORY -->
                                    <div class="pricing-5-category mb-4" style="margin-bottom: 12px;">
                                        <h3 style="font-size: 14px; font-weight: 600; margin: 0; color: #333;">Category {{ $i + 1 }}</h3>
                                    </div>

                                    <!-- SERVICES LIST -->
                                    <ul class="pricing-list" style="list-style: none; padding: 0; margin: 0;">
                                        @for($j = 0; $j < 3; $j++)
                                            <li class="pricing-5-item" style="margin-bottom: 8px;">
                                                <div class="detail-price" style="display: flex; align-items: center; justify-content: space-between;">
                                                    <div class="price-name">
                                                        <p style="font-size: 11px; margin: 0; color: #333;">
                                                            Service Name {{ $j + 1 }}
                                                            <span class="salon-availability" style="display: inline;">
                                                                <i class="fas fa-question-circle service-info-icon" style="font-size: 10px; color: #666; margin-left: 4px;" title="Click for details"></i>
                                                            </span>
                                                        </p>
                                                    </div>
                                                    <div class="price-dots" style="flex: 1; border-bottom: 1px dotted #ccc; margin: 0 8px;"></div>
                                                    <div class="price-number">
                                                        <p class="salon-price" style="font-size: 11px; margin: 0; color: #333; font-weight: 600;">
                                                            <span class="icon-saudi_riyal">ر.س</span> {{ (50 + ($j * 25)) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        @endfor
                                    </ul>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- BUTTON -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col">
                            <div class="more-btn mt-5" style="text-align: center;">
                                <a href="{{ $content['buttonUrl'] ?? '#' }}" class="btn btn--tra-black hover--black" style="background: transparent; color: #333; border: 2px solid #333; padding: 10px 20px; border-radius: 4px; font-size: 12px; font-weight: 600; text-decoration: none; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block;">
                                    {{ $content['buttonText'] ?? 'View All Prices' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                {{-- Default/Unknown Section --}}
                <div style="padding: 40px; text-align: center; color: #666;">
                    <div style="border: 2px dashed #ddd; padding: 30px; border-radius: 8px; background: #f8f9fa;">
                        <i class="fas fa-home" style="font-size: 32px; margin-bottom: 12px; color: #ccc;"></i>
                        <h6 style="margin: 0 0 4px; color: #666;">
                            {{ $section ? ucfirst(str_replace('_', ' ', $section)) : 'Select Section Type' }}
                        </h6>
                        <small style="color: #999;">
                            Choose a home section type to see preview
                        </small>
                        @if($content['title'] ?? $content['smallTitle'] ?? null)
                            <div style="margin-top: 8px; font-size: 12px; color: #999;">
                                "{{ $content['title'] ?? $content['smallTitle'] }}"
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
                    <strong>Section:</strong> {{ $section ? (App\Models\HomeSection::getSectionNameMapping()[$section] ?? ucfirst(str_replace('_', ' ', $section))) : '—' }}
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
                        $imageKeys = ['bgImage', 'image', 'image1', 'image2', 'image3'];
                        // Check slides array for hero section
                        if ($section === 'hero_section' && !empty($content['slides'])) {
                            foreach ($content['slides'] as $index => $slide) {
                                $imageKeys[] = 'slides.' . $index . '.bgImage';
                            }
                        }
                        // Check services array for services section
                        if ($section === 'services_section' && !empty($content['services'])) {
                            foreach ($content['services'] as $index => $service) {
                                $imageKeys[] = 'services.' . $index . '.image';
                            }
                        }
                        
                        $hasImages = collect($imageKeys)->some(fn($key) => $getBestImageUrl($key) || $getNestedImageUrl($key));
                    @endphp
                    <span style="color: {{ $hasImages ? '#28a745' : '#999' }};">
                        {{ $hasImages ? 'Uploaded' : 'None' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>