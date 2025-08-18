<?php

namespace Database\Seeders;

use App\Models\ProductAndService;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ProductAndServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // Cutting & Styling Services
            [
                'category_name' => 'Cutting & Styling',
                'point_of_sale_id' => 1,
                'name_en' => "Women's Haircut",
                'name_ar' => 'قص شعر النساء',
                'description_en' => '<h2>Experience the Perfect Women\'s Haircut</h2>
<p>At our premium salon, we specialize in creating stunning hairstyles tailored to your unique features and preferences. Our women\'s haircut service is designed to enhance your natural beauty while keeping up with the latest trends.</p>

<h3>What We Offer:</h3>
<ul>
  <li><strong>Personalized Consultation:</strong> Our expert stylists begin with an in-depth consultation to understand your hair type, face shape, and lifestyle needs.</li>
  <li><strong>Premium Techniques:</strong> We utilize precision cutting techniques that ensure your hair moves naturally and maintains its shape as it grows.</li>
  <li><strong>Versatile Styling:</strong> Whether you desire classic elegance, contemporary chic, or avant-garde creativity, we can deliver your perfect look.</li>
  <li><strong>Comprehensive Service:</strong> Each haircut includes a relaxing shampoo, conditioning treatment, and professional styling.</li>
</ul>

<h3>Our Professional Approach</h3>
<p>We believe that the perfect haircut should:</p>
<ol>
  <li>Enhance your best features</li>
  <li>Complement your lifestyle</li>
  <li>Be easy to maintain at home</li>
  <li>Make you feel confident and beautiful</li>
</ol>

<p>Our team stays current with emerging trends through continuous education and international training, ensuring you receive the most innovative and flattering styles available.</p>

<blockquote>
  <p>"The right haircut can transform not just your appearance, but how you feel about yourself every day."</p>
</blockquote>

<h3>Aftercare Recommendations</h3>
<p>To maintain your beautiful new hairstyle between visits, we provide personalized product recommendations and styling tutorials tailored to your specific hair type and cut.</p>',

                'description_ar' => '<h2>استمتعي بتجربة قص شعر مثالية للنساء</h2>
<p>في صالوننا الفاخر، نتخصص في إنشاء تسريحات شعر مذهلة مصممة خصيصًا لملامحك وتفضيلاتك الفريدة. خدمة قص الشعر للسيدات مصممة لتعزيز جمالك الطبيعي مع مواكبة أحدث الاتجاهات.</p>

<h3>ما نقدمه:</h3>
<ul>
  <li><strong>استشارة شخصية:</strong> يبدأ مصففونا الخبراء باستشارة متعمقة لفهم نوع شعرك وشكل وجهك واحتياجات نمط حياتك.</li>
  <li><strong>تقنيات متميزة:</strong> نستخدم تقنيات قص دقيقة تضمن حركة شعرك بشكل طبيعي والحفاظ على شكله أثناء نموه.</li>
  <li><strong>تصفيف متنوع:</strong> سواء كنت ترغبين في أناقة كلاسيكية، أو عصرية أنيقة، أو إبداع طليعي، يمكننا تحقيق مظهرك المثالي.</li>
  <li><strong>خدمة شاملة:</strong> يشمل كل قصة شعر غسيل شعر مريح، وعلاج ترطيب، وتصفيف احترافي.</li>
</ul>

<h3>نهجنا الاحترافي</h3>
<p>نحن نؤمن بأن قصة الشعر المثالية يجب أن:</p>
<ol>
  <li>تعزز أفضل ملامحك</li>
  <li>تكمل أسلوب حياتك</li>
  <li>تكون سهلة الصيانة في المنزل</li>
  <li>تجعلك تشعرين بالثقة والجمال</li>
</ol>

<p>يظل فريقنا على اطلاع بالاتجاهات الناشئة من خلال التعليم المستمر والتدريب الدولي، مما يضمن حصولك على أكثر الأساليب ابتكارًا وإطراءً المتاحة.</p>

<blockquote>
  <p>"يمكن لقصة الشعر المناسبة أن تغير ليس فقط مظهرك، بل كيف تشعرين تجاه نفسك كل يوم."</p>
</blockquote>

<h3>توصيات العناية اللاحقة</h3>
<p>للحفاظ على تسريحة شعرك الجميلة الجديدة بين الزيارات، نقدم توصيات منتجات شخصية ودروس تصفيف مصممة خصيصًا لنوع شعرك وقصتك المحددة.</p>',
                'price' => 68,
                'price_home' => 153,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 1,
                'duration_minutes' => 60,
                'image' => 'services/Women\'s Haircut.jpg',
            ],
            [
                'category_name' => 'Cutting & Styling',
                'point_of_sale_id' => 1,
                'name_en' => "Men's Haircut",
                'name_ar' => 'قص شعر الرجال',
                'description_en' => '<h2>Premium Men\'s Haircut Experience</h2>
<p>Our men\'s haircut service goes beyond the ordinary, providing a tailored grooming experience that combines traditional barbering expertise with contemporary styling techniques.</p>

<h3>Service Highlights:</h3>
<ul>
  <li><strong>Expert Consultation:</strong> We begin with a thorough discussion of your style preferences, facial features, and hair type to determine the perfect cut.</li>
  <li><strong>Precision Cutting:</strong> Using both traditional and modern techniques, our barbers deliver immaculate lines, fades, and textures.</li>
  <li><strong>Hot Towel Service:</strong> Enjoy the luxury of hot towel treatment that opens pores and softens hair for the closest, most comfortable cut.</li>
  <li><strong>Styling Guidance:</strong> Learn how to maintain your new look with professional styling tips tailored to your specific haircut.</li>
</ul>

<h3>Our Techniques</h3>
<p>We specialize in a variety of cutting methods including:</p>
<ol>
  <li>Classic scissor-over-comb</li>
  <li>Modern precision fading</li>
  <li>Texturizing for added dimension</li>
  <li>Beard trimming and shaping to complement your haircut</li>
</ol>

<p>Each service is performed using premium tools and products to ensure the highest quality results and experience.</p>

<blockquote>
  <p>"A great haircut is an investment in yourself that pays dividends in confidence every day."</p>
</blockquote>

<h3>The Complete Experience</h3>
<p>Your service includes a relaxing shampoo with scalp massage, precision cutting, styling, and finish with selected premium products. For those who desire it, we also offer complimentary beard trimming and eyebrow grooming to complete your refined look.</p>',

                'description_ar' => '<h2>تجربة قص شعر رجالي متميزة</h2>
<p>تتجاوز خدمة قص الشعر للرجال لدينا المألوف، حيث توفر تجربة عناية مخصصة تجمع بين خبرة الحلاقة التقليدية وتقنيات التصفيف المعاصرة.</p>

<h3>مميزات الخدمة:</h3>
<ul>
  <li><strong>استشارة خبيرة:</strong> نبدأ بمناقشة شاملة لتفضيلات أسلوبك وملامح وجهك ونوع شعرك لتحديد القصة المثالية.</li>
  <li><strong>قص دقيق:</strong> باستخدام التقنيات التقليدية والحديثة، يقدم حلاقونا خطوطًا وتلاشيًا وقوامًا لا تشوبه شائبة.</li>
  <li><strong>خدمة المناشف الساخنة:</strong> استمتع برفاهية علاج المناشف الساخنة الذي يفتح المسام ويلين الشعر للحصول على قصة أقرب وأكثر راحة.</li>
  <li><strong>توجيهات التصفيف:</strong> تعلم كيفية الحفاظ على مظهرك الجديد مع نصائح تصفيف احترافية مصممة خصيصًا لقصة شعرك المحددة.</li>
</ul>

<h3>تقنياتنا</h3>
<p>نحن متخصصون في مجموعة متنوعة من طرق القص بما في ذلك:</p>
<ol>
  <li>المقص الكلاسيكي فوق المشط</li>
  <li>التلاشي الدقيق الحديث</li>
  <li>إضافة القوام لأبعاد إضافية</li>
  <li>تشذيب وتشكيل اللحية لتكملة قصة شعرك</li>
</ol>

<p>يتم تنفيذ كل خدمة باستخدام أدوات ومنتجات متميزة لضمان أعلى جودة للنتائج والتجربة.</p>

<blockquote>
  <p>"قصة الشعر الرائعة هي استثمار في نفسك يؤتي ثماره في الثقة كل يوم."</p>
</blockquote>

<h3>التجربة الكاملة</h3>
<p>تتضمن خدمتك غسيل شعر مريح مع تدليك فروة الرأس، وقص دقيق، وتصفيف، وتشطيب بمنتجات متميزة مختارة. لأولئك الذين يرغبون في ذلك، نقدم أيضًا تشذيب اللحية وتهذيب الحواجب مجانًا لإكمال مظهرك المتميز.</p>',
                'price' => 45,
                'price_home' => 92,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 2,
                'duration_minutes' => 30,
                'image' => 'services/Men\'s Haircut.jpg',
            ],
            [
                'category_name' => 'Cutting & Styling',
                'point_of_sale_id' => 1,
                'name_en' => 'Blow Dry',
                'name_ar' => 'تنشيف الشعر',
                'description_en' => '<h2>Luxurious Blow Dry Service</h2>
<p>Transform your hair with our professional blow dry service. More than just drying your hair, our expert stylists create volume, smoothness, and movement tailored to your hair type and desired look.</p>

<h3>What to Expect:</h3>
<ul>
  <li><strong>Customized Approach:</strong> We assess your hair\'s texture, density, and natural tendencies to determine the perfect technique.</li>
  <li><strong>Premium Products:</strong> We use high-quality heat protectants, volumizers, and finishing products suited to your specific hair type.</li>
  <li><strong>Skilled Technique:</strong> Our stylists are trained in the latest sectioning and round brush methods to maximize shine and longevity.</li>
  <li><strong>Style Options:</strong> Choose from sleek and straight, voluminous, bouncy curls, or beachy waves.</li>
</ul>

<h3>Our Professional Process</h3>
<p>Every blow dry service includes:</p>
<ol>
  <li>Thorough cleansing with shampoo selected for your hair type</li>
  <li>Conditioning treatment to prepare hair for styling</li>
  <li>Application of heat-protective and styling products</li>
  <li>Sectional drying with professional techniques</li>
  <li>Finishing touches for a polished look</li>
</ol>

<p>The result is beautifully styled hair that looks and feels incredible, with movement and shine that lasts for days.</p>

<blockquote>
  <p>"A professional blow dry doesn\'t just style your hair—it transforms your entire look and boosts your confidence."</p>
</blockquote>

<h3>Perfect For Special Occasions</h3>
<p>While many clients enjoy our blow dry services as part of their regular beauty routine, it\'s also perfect for preparing for special events, important meetings, or anytime you want to look and feel your absolute best.</p>',

                'description_ar' => '<h2>خدمة تنشيف الشعر الفاخرة</h2>
<p>غيري شعرك مع خدمة تنشيف الشعر الاحترافية لدينا. أكثر من مجرد تجفيف شعرك، يخلق مصففونا الخبراء حجمًا ونعومة وحركة مصممة خصيصًا لنوع شعرك والمظهر الذي ترغبين فيه.</p>

<h3>ما يمكن توقعه:</h3>
<ul>
  <li><strong>نهج مخصص:</strong> نقيم قوام شعرك وكثافته وميوله الطبيعية لتحديد التقنية المثالية.</li>
  <li><strong>منتجات متميزة:</strong> نستخدم واقيات حرارة عالية الجودة، ومكثفات، ومنتجات تشطيب مناسبة لنوع شعرك المحدد.</li>
  <li><strong>تقنية ماهرة:</strong> تم تدريب مصففينا على أحدث طرق التقسيم والفرشاة المستديرة لتعظيم اللمعان والمتانة.</li>
  <li><strong>خيارات الأسلوب:</strong> اختاري من بين الأملس والمستقيم، أو الحجم، أو التجعيدات المرنة، أو الأمواج الشاطئية.</li>
</ul>

<h3>عمليتنا الاحترافية</h3>
<p>تتضمن كل خدمة تنشيف الشعر:</p>
<ol>
  <li>تنظيف شامل بشامبو مختار لنوع شعرك</li>
  <li>علاج التكييف لتحضير الشعر للتصفيف</li>
  <li>تطبيق منتجات واقية من الحرارة ومنتجات التصفيف</li>
  <li>تجفيف قطاعي بتقنيات احترافية</li>
  <li>لمسات نهائية لمظهر متقن</li>
</ol>

<p>النتيجة هي شعر مصفف بشكل جميل يبدو ويشعر بشكل لا يصدق، مع حركة ولمعان يدوم لأيام.</p>

<blockquote>
  <p>"التنشيف الاحترافي لا يصفف شعرك فحسب—بل يحول مظهرك بالكامل ويعزز ثقتك."</p>
</blockquote>

<h3>مثالي للمناسبات الخاصة</h3>
<p>بينما يستمتع العديد من العملاء بخدمات تنشيف الشعر لدينا كجزء من روتين الجمال المنتظم، فهي أيضًا مثالية للتحضير للمناسبات الخاصة، أو الاجتماعات المهمة، أو في أي وقت تريدين أن تبدي وتشعري بأفضل حالاتك.</p>',
                'price' => 44,
                'price_home' => null,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => false,
                'sort_order' => 3,
                'duration_minutes' => 30,
                'image' => 'services/Blow Dry.jpg',
            ],
            [
                'category_name' => 'Cutting & Styling',
                'point_of_sale_id' => 1,
                'name_en' => 'Updo/Style',
                'name_ar' => 'تصفيف الشعر',
                'description_en' => '<h2>Elegant Updo & Styling Services</h2>
<p>Our expert stylists specialize in creating stunning updos and hairstyles perfect for weddings, galas, photoshoots, and any special occasion that demands an extraordinary look.</p>

<h3>Styling Options:</h3>
<ul>
  <li><strong>Classic Updos:</strong> Timeless elegance with French twists, chignons, and sophisticated buns.</li>
  <li><strong>Modern Styles:</strong> Contemporary interpretations of classic styles with creative elements and unique touches.</li>
  <li><strong>Braided Creations:</strong> Intricate braiding techniques from simple accents to full braided updos.</li>
  <li><strong>Red Carpet Looks:</strong> Celebrity-inspired styles that make a statement and capture attention.</li>
  <li><strong>Vintage Glamour:</strong> Retro-inspired looks from the roaring 20s to the swinging 60s.</li>
</ul>

<h3>Our Styling Process</h3>
<p>Each updo appointment includes:</p>
<ol>
  <li>Detailed consultation to understand your vision, outfit, and event</li>
  <li>Pre-styling preparation with appropriate products</li>
  <li>Professional styling using premium tools and techniques</li>
  <li>Setting with high-performance hairsprays and finishers</li>
  <li>Final touches and adjustments to ensure perfection</li>
</ol>

<p>We recommend bringing photos of styles you love to your appointment, along with any hair accessories or headpieces you plan to wear.</p>

<blockquote>
  <p>"When your hair is styled to perfection, it becomes the ultimate accessory to your outfit and the perfect frame for your face."</p>
</blockquote>

<h3>Special Event Packages</h3>
<p>For weddings and formal events, we offer special packages that include trials, day-of styling for you and your party, and touch-up kits to keep your look flawless throughout your event. Ask about our on-location services for the ultimate convenience on your special day.</p>',

                'description_ar' => '<h2>خدمات تصفيف الشعر والتسريحات المرفوعة الأنيقة</h2>
<p>يتخصص مصففو الشعر الخبراء لدينا في إنشاء تسريحات شعر مرفوعة وأنماط مذهلة مثالية لحفلات الزفاف، والحفلات الراقصة، وجلسات التصوير، وأي مناسبة خاصة تتطلب مظهرًا استثنائيًا.</p>

<h3>خيارات التصفيف:</h3>
<ul>
  <li><strong>تسريحات مرفوعة كلاسيكية:</strong> أناقة خالدة مع لفات فرنسية، وكعكات شينيون، وكعكات متطورة.</li>
  <li><strong>الأساليب الحديثة:</strong> تفسيرات معاصرة للأنماط الكلاسيكية مع عناصر إبداعية ولمسات فريدة.</li>
  <li><strong>إبداعات مضفرة:</strong> تقنيات ضفر معقدة من لمسات بسيطة إلى تسريحات مرفوعة مضفرة بالكامل.</li>
  <li><strong>إطلالات السجادة الحمراء:</strong> أنماط مستوحاة من المشاهير تصنع بيانًا وتلفت الانتباه.</li>
  <li><strong>سحر قديم:</strong> مظاهر مستوحاة من الطراز القديم من العشرينات الصاخبة إلى الستينات المتأرجحة.</li>
</ul>

<h3>عملية التصفيف لدينا</h3>
<p>تشمل كل موعد تصفيف:</p>
<ol>
  <li>استشارة مفصلة لفهم رؤيتك وزيك والمناسبة</li>
  <li>تحضير ما قبل التصفيف بمنتجات مناسبة</li>
  <li>تصفيف احترافي باستخدام أدوات وتقنيات متميزة</li>
  <li>تثبيت بمثبتات شعر عالية الأداء ومنتجات تشطيب</li>
  <li>لمسات نهائية وتعديلات لضمان الكمال</li>
</ol>

<p>نوصي بإحضار صور للأنماط التي تحبينها إلى موعدك، إلى جانب أي إكسسوارات شعر أو قطع رأس تخططين لارتدائها.</p>

<blockquote>
  <p>"عندما يتم تصفيف شعرك إلى حد الكمال، يصبح الإكسسوار النهائي لزيك والإطار المثالي لوجهك."</p>
</blockquote>

<h3>باقات المناسبات الخاصة</h3>
<p>لحفلات الزفاف والمناسبات الرسمية، نقدم باقات خاصة تشمل تجارب، وتصفيف يوم المناسبة لك ولمجموعتك، ومجموعات لمسات سريعة للحفاظ على مظهرك مثاليًا طوال فعاليتك. اسألي عن خدماتنا في الموقع للحصول على أقصى قدر من الراحة في يومك الخاص.</p>',
                'price' => 75,
                'price_home' => 164,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 4,
                'duration_minutes' => 60,
                'image' => 'services/Updo Style.jpg',
            ],
            [
                'category_name' => 'Cutting & Styling',
                'point_of_sale_id' => 1,
                'name_en' => "Child's Haircut",
                'name_ar' => 'قص شعر الأطفال',
                'description_en' => '<h2>Kid-Friendly Haircut Experience</h2>
<p>Our child\'s haircut service is specially designed to create a positive, fun experience for children while delivering quality cuts that both parents and kids will love.</p>

<h3>Child-Centered Approach:</h3>
<ul>
  <li><strong>Gentle Techniques:</strong> Our stylists are trained in child-friendly approaches that minimize stress and maximize comfort.</li>
  <li><strong>Engaging Environment:</strong> Kid-friendly chairs, entertainment options, and a welcoming atmosphere help children feel at ease.</li>
  <li><strong>Patient Stylists:</strong> Our team understands that children may need extra time and reassurance during their haircut.</li>
  <li><strong>Age-Appropriate Styles:</strong> We offer trendy, practical, and age-appropriate cuts for children of all ages.</li>
</ul>

<h3>Our Special Touches</h3>
<p>What makes our children\'s haircuts special:</p>
<ol>
  <li>First-time haircut certificates to commemorate this milestone</li>
  <li>Small rewards or treats after the service</li>
  <li>Photo opportunities to capture the moment</li>
  <li>Styling tips for parents to maintain the look at home</li>
  <li>Child-friendly products that are gentle on sensitive scalps</li>
</ol>

<p>We\'ve designed our service to help children develop positive associations with haircuts from an early age, setting the foundation for a lifetime of good grooming habits.</p>

<blockquote>
  <p>"A child\'s first experiences with haircuts can shape their feelings about grooming for years to come. We make sure those experiences are positive ones."</p>
</blockquote>

<h3>Special Accommodations</h3>
<p>We understand that some children may have sensory sensitivities or special needs. Our stylists are trained to accommodate various requirements and can provide quieter appointment times, sensory-friendly tools, and individualized approaches. Please mention any special needs when booking so we can ensure the best experience for your child.</p>',

                'description_ar' => '<h2>تجربة قص شعر صديقة للأطفال</h2>
<p>خدمة قص شعر الأطفال لدينا مصممة خصيصًا لخلق تجربة إيجابية وممتعة للأطفال مع تقديم قصات جودة يحبها الآباء والأطفال.</p>

<h3>نهج متمركز حول الطفل:</h3>
<ul>
  <li><strong>تقنيات لطيفة:</strong> مصففونا مدربون على أساليب صديقة للأطفال تقلل التوتر وتزيد الراحة.</li>
  <li><strong>بيئة جذابة:</strong> كراسي صديقة للأطفال، وخيارات ترفيه، وأجواء ترحيبية تساعد الأطفال على الشعور بالراحة.</li>
  <li><strong>مصففون صبورون:</strong> يفهم فريقنا أن الأطفال قد يحتاجون إلى وقت إضافي وطمأنة أثناء قص شعرهم.</li>
  <li><strong>أنماط مناسبة للعمر:</strong> نقدم قصات عصرية وعملية ومناسبة للعمر للأطفال من جميع الأعمار.</li>
</ul>

<h3>لمساتنا الخاصة</h3>
<p>ما يجعل قصات شعر الأطفال لدينا مميزة:</p>
<ol>
  <li>شهادات قصة الشعر الأولى للاحتفال بهذا الإنجاز</li>
  <li>مكافآت صغيرة أو حلوى بعد الخدمة</li>
  <li>فرص التقاط الصور لتوثيق اللحظة</li>
  <li>نصائح تصفيف للوالدين للحفاظ على المظهر في المنزل</li>
  <li>منتجات صديقة للأطفال لطيفة على فروة الرأس الحساسة</li>
</ol>

<p>لقد صممنا خدمتنا لمساعدة الأطفال على تطوير ارتباطات إيجابية مع قصات الشعر من سن مبكرة، ووضع الأساس لعادات العناية الجيدة مدى الحياة.</p>

<blockquote>
  <p>"يمكن أن تشكل تجارب الطفل الأولى مع قصات الشعر مشاعرهم حول العناية لسنوات قادمة. نحن نتأكد من أن تلك التجارب إيجابية."</p>
</blockquote>

<h3>تسهيلات خاصة</h3>
<p>نحن نفهم أن بعض الأطفال قد يكون لديهم حساسيات حسية أو احتياجات خاصة. مصففونا مدربون على استيعاب المتطلبات المختلفة ويمكنهم توفير مواعيد أكثر هدوءًا، وأدوات صديقة للحواس، ونهج فردية. يرجى ذكر أي احتياجات خاصة عند الحجز حتى نتمكن من ضمان أفضل تجربة لطفلك.</p>',
                'price' => 32,
                'price_home' => 68,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 5,
                'duration_minutes' => 30,
                'image' => 'services/Child\'s Haircut.jpg',
            ],

            // Hair Coloring Services
            [
                'category_name' => 'Hair Coloring',
                'point_of_sale_id' => 1,
                'name_en' => 'Single Process',
                'name_ar' => 'تلوين بسيط',
                'description_en' => '<h2>Professional Single Process Hair Coloring</h2>
<p>Our single process hair coloring service delivers rich, dimensional color in one simple application. Whether you\'re looking to enhance your natural shade, cover gray hair, or make a subtle change, our expert colorists create beautiful, lasting results.</p>

<h3>Service Features:</h3>
<ul>
  <li><strong>Expert Color Analysis:</strong> Our colorists assess your skin tone, eye color, and natural hair shade to recommend the most flattering color options.</li>
  <li><strong>Premium Color Products:</strong> We use high-quality, ammonia-free formulas that minimize damage while maximizing color vibrancy and longevity.</li>
  <li><strong>Customized Formulation:</strong> Each color is custom-mixed for your specific hair needs and desired outcome.</li>
  <li><strong>Gray Coverage:</strong> Specialized techniques ensure complete and natural-looking coverage of gray hair.</li>
</ul>

<h3>The Color Process</h3>
<p>Our comprehensive single process service includes:</p>
<ol>
  <li>In-depth consultation to understand your color goals</li>
  <li>Custom color formulation tailored to your hair</li>
  <li>Professional application using advanced techniques</li>
  <li>Processing time with periodic checks for optimal development</li>
  <li>Specialized shampoo and conditioning treatment</li>
  <li>Blowdry and style to showcase your new color</li>
</ol>

<p>Each service also includes aftercare recommendations and product suggestions to maintain your color\'s vibrancy between salon visits.</p>

<blockquote>
  <p>"The right hair color doesn\'t just change your look - it illuminates your complexion, enhances your eyes, and can even transform how you feel about yourself."</p>
</blockquote>

<h3>Color Maintenance</h3>
<p>To extend the life of your color, we recommend scheduling touch-ups every 4-6 weeks for maximum vibrancy and gray coverage. We also offer color-safe home care products that protect your investment and keep your color looking fresh longer.</p>',

                'description_ar' => '<h2>تلوين شعر احترافي بعملية واحدة</h2>
<p>توفر خدمة تلوين الشعر بعملية واحدة لدينا لونًا غنيًا وثلاثي الأبعاد في تطبيق بسيط واحد. سواء كنت تتطلعين إلى تعزيز لونك الطبيعي، أو تغطية الشعر الرمادي، أو إجراء تغيير خفيف، يخلق خبراء الألوان لدينا نتائج جميلة ودائمة.</p>

<h3>ميزات الخدمة:</h3>
<ul>
  <li><strong>تحليل لون الخبراء:</strong> يقيم خبراء الألوان لدينا لون بشرتك، ولون عينيك، ودرجة شعرك الطبيعية للتوصية بخيارات الألوان الأكثر إطراءً.</li>
  <li><strong>منتجات لون متميزة:</strong> نستخدم تركيبات عالية الجودة وخالية من الأمونيا تقلل الضرر مع تعظيم حيوية اللون ومتانته.</li>
  <li><strong>تركيبة مخصصة:</strong> يتم خلط كل لون خصيصًا لاحتياجات شعرك المحددة والنتيجة المرجوة.</li>
  <li><strong>تغطية الشعر الرمادي:</strong> تقنيات متخصصة تضمن تغطية كاملة وطبيعية المظهر للشعر الرمادي.</li>
</ul>

<h3>عملية التلوين</h3>
<p>تشمل خدمة العملية الواحدة الشاملة لدينا:</p>
<ol>
  <li>استشارة متعمقة لفهم أهداف اللون الخاصة بك</li>
  <li>تركيبة لون مخصصة مصممة لشعرك</li>
  <li>تطبيق احترافي باستخدام تقنيات متقدمة</li>
  <li>وقت المعالجة مع فحوصات دورية للتطور الأمثل</li>
  <li>شامبو متخصص وعلاج تكييف</li>
  <li>تجفيف وتصفيف لعرض لونك الجديد</li>
</ol>

<p>تتضمن كل خدمة أيضًا توصيات للعناية اللاحقة واقتراحات المنتجات للحفاظ على حيوية لونك بين زيارات الصالون.</p>

<blockquote>
  <p>"لون الشعر المناسب لا يغير مظهرك فحسب - بل يضيء بشرتك، ويعزز عينيك، ويمكن حتى أن يغير شعورك تجاه نفسك."</p>
</blockquote>

<h3>صيانة اللون</h3>
<p>لإطالة عمر لونك، نوصي بجدولة لمسات الإصلاح كل 4-6 أسابيع للحصول على أقصى قدر من الحيوية وتغطية الشعر الرمادي. نقدم أيضًا منتجات العناية المنزلية الآمنة للألوان التي تحمي استثمارك وتحافظ على مظهر لونك طازجًا لفترة أطول.</p>',
                'price' => 130,
                'price_home' => 195,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 1,
                'duration_minutes' => 90,
                'image' => 'services/Single Process.jpg',
            ],
            [
                'category_name' => 'Hair Coloring',
                'point_of_sale_id' => 1,
                'name_en' => 'Double Process',
                'name_ar' => 'تلوين مزدوج',
                'description_en' => '<h2>Double Process Hair Coloring</h2>
<p>Our double process coloring service provides dramatic transformations for those seeking a significant change. This two-step technique involves lightening the hair first, then applying your desired tone for truly remarkable results.</p>
<p>Perfect for transitioning to lighter shades, fashion colors, or achieving platinum blonde. Our expert colorists ensure your hair remains healthy and vibrant throughout this intensive process.</p>',
                'description_ar' => '<h2>تلوين الشعر بعملية مزدوجة</h2>
<p>توفر خدمة التلوين بعملية مزدوجة تحولات دراماتيكية لأولئك الذين يتطلعون إلى تغيير كبير. تتضمن هذه التقنية ذات الخطوتين تفتيح الشعر أولاً، ثم تطبيق اللون المطلوب للحصول على نتائج مذهلة حقًا.</p>
<p>مثالية للانتقال إلى الظلال الأفتح، أو ألوان الموضة، أو تحقيق اللون الأشقر البلاتيني. يضمن خبراء الألوان لدينا بقاء شعرك صحيًا ونابضًا بالحياة طوال هذه العملية المكثفة.</p>',
                'price' => 290,
                'price_home' => 375,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 2,
                'duration_minutes' => 120,
                'image' => 'services/Double Process.jpg',
            ],
            [
                'category_name' => 'Hair Coloring',
                'point_of_sale_id' => 1,
                'name_en' => 'Full Head Highlights',
                'name_ar' => 'تلوين كامل الرأس',
                'description_en' => '<h2>Full Head Highlighting Service</h2>
<p>Transform your look with our comprehensive full head highlighting service. Our skilled colorists apply highlights throughout your entire head, creating dimension, depth, and radiant color that enhances your natural beauty.</p>
<p>Using foil or balayage techniques, we customize the highlight placement to complement your face shape and desired style, resulting in a stunning, multi-dimensional finish.</p>',
                'description_ar' => '<h2>خدمة إبراز لون كامل الرأس</h2>
<p>غيري مظهرك مع خدمة إبراز اللون الشاملة لكامل الرأس. يقوم خبراء الألوان المهرة لدينا بوضع الإبراز في جميع أنحاء رأسك، مما يخلق أبعادًا وعمقًا ولونًا مشعًا يعزز جمالك الطبيعي.</p>
<p>باستخدام تقنيات رقائق الألمنيوم أو البالياج، نقوم بتخصيص وضع الإبراز ليكمل شكل وجهك والأسلوب المطلوب، مما يؤدي إلى لمسة نهائية مذهلة متعددة الأبعاد.</p>',
                'price' => 345,
                'price_home' => 380,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 3,
                'duration_minutes' => 120,
                'image' => 'services/Full Head Highlights.jpg',
            ],
            [
                'category_name' => 'Hair Coloring',
                'point_of_sale_id' => 1,
                'name_en' => 'Half Head Highlights',
                'name_ar' => 'تلوين نصف الرأس',
                'description_en' => '<h2>Half Head Highlighting Service</h2>
<p>Our half head highlighting service offers a subtle yet effective color enhancement by focusing on the top and crown sections of your hair. This technique is perfect for those wanting dimension and brightness without a complete color overhaul.</p>
<p>Ideal for first-time highlight clients, those on a budget, or for maintaining your color between full highlight appointments.</p>',
                'description_ar' => '<h2>خدمة إبراز لون نصف الرأس</h2>
<p>توفر خدمة إبراز لون نصف الرأس تحسينًا لونيًا خفيفًا ولكنه فعال من خلال التركيز على الأقسام العلوية وتاج شعرك. هذه التقنية مثالية لأولئك الذين يرغبون في الحصول على أبعاد وإشراق دون تغيير كامل للون.</p>
<p>مثالية لعملاء الإبراز للمرة الأولى، أو أولئك الذين لديهم ميزانية محدودة، أو للحفاظ على لونك بين مواعيد الإبراز الكامل.</p>',
                'price' => 260,
                'price_home' => 290,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 4,
                'duration_minutes' => 90,
                'image' => 'services/Half Head Highlights.jpg',
            ],
            [
                'category_name' => 'Hair Coloring',
                'point_of_sale_id' => 1,
                'name_en' => 'Balayage',
                'name_ar' => 'بالياج',
                'description_en' => '<h2>Balayage Hair Coloring</h2>
<p>Experience our expert balayage technique, a freehand coloring method that creates natural-looking, sun-kissed highlights. This low-maintenance approach gradually lightens hair from mid-lengths to ends, producing a soft, blended effect without harsh lines.</p>
<p>Balayage grows out beautifully, requiring fewer touch-ups than traditional highlighting, and can be customized to complement any hair color and texture.</p>',
                'description_ar' => '<h2>تلوين الشعر بتقنية البالياج</h2>
<p>جربي تقنية البالياج المتخصصة لدينا، وهي طريقة تلوين حر تخلق إبرازات تبدو طبيعية وكأنها مقبلة بأشعة الشمس. يقوم هذا النهج منخفض الصيانة بتفتيح الشعر تدريجيًا من منتصف الطول إلى الأطراف، مما ينتج تأثيرًا ناعمًا ومتناغمًا دون خطوط حادة.</p>
<p>ينمو البالياج بشكل جميل، مما يتطلب لمسات إصلاح أقل من الإبراز التقليدي، ويمكن تخصيصه ليكمل أي لون ونسيج للشعر.</p>',
                'price' => 85,
                'price_home' => 220,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 5,
                'duration_minutes' => 120,
                'image' => 'services/Balayage.jpg',
            ],
            [
                'category_name' => 'Hair Coloring',
                'point_of_sale_id' => 1,
                'name_en' => 'Color Refresh',
                'name_ar' => 'تجديد اللون',
                'description_en' => '<h2>Color Refresh Service</h2>
<p>Revitalize your hair color with our specialized color refresh service. This quick treatment restores vibrancy to faded color, enhances tone, and adds stunning shine between your regular coloring appointments.</p>
<p>Our professional color-enhancing formulas are gentle on your hair while delivering noticeable results that extend the life of your original color treatment.</p>',
                'description_ar' => '<h2>خدمة تجديد اللون</h2>
<p>أعيدي الحيوية إلى لون شعرك مع خدمة تجديد اللون المتخصصة لدينا. يستعيد هذا العلاج السريع الحيوية للون الباهت، ويعزز درجة اللون، ويضيف لمعانًا مذهلاً بين مواعيد التلوين المنتظمة.</p>
<p>صيغنا المهنية المعززة للون لطيفة على شعرك مع تقديم نتائج ملحوظة تمد عمر علاج اللون الأصلي الخاص بك.</p>',
                'price' => 95,
                'price_home' => 130,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 6,
                'duration_minutes' => 60,
                'image' => 'services/Color Refresh.jpg',
            ],

            // Hair Treatments
            [
                'category_name' => 'Hair Treatments',
                'point_of_sale_id' => 2,
                'name_en' => 'Brazilian Blow Out',
                'name_ar' => 'تصفيف البرازيلي',
                'description_en' => '<h2>Brazilian Blowout Smoothing Treatment</h2>
<p>Our professional Brazilian Blowout treatment is the ultimate solution for frizzy, unmanageable hair. This revolutionary smoothing system creates a protective protein layer around each hair strand, eliminating frizz and delivering remarkable smoothness and shine.</p>

<h3>Benefits of Brazilian Blowout:</h3>
<ul>
  <li><strong>Eliminates Frizz:</strong> Say goodbye to frizz even in humid conditions.</li>
  <li><strong>Reduces Styling Time:</strong> Cut your blow-dry time by up to 50%.</li>
  <li><strong>Preserves Natural Movement:</strong> Unlike some keratin treatments, Brazilian Blowout maintains your hair\'s natural body and movement.</li>
  <li><strong>Immediate Results:</strong> No waiting period - your hair is ready to wash, style, and enjoy immediately after treatment.</li>
  <li><strong>Customizable Results:</strong> Can be tailored to your specific hair needs from subtle smoothing to dramatic straightening.</li>
</ul>

<h3>Our Treatment Process</h3>
<p>The Brazilian Blowout service includes:</p>
<ol>
  <li>Clarifying shampoo to prepare the hair</li>
  <li>Application of the Brazilian Blowout professional solution</li>
  <li>Blow-dry with professional techniques</li>
  <li>Flat iron process to seal the treatment</li>
  <li>Final rinse and application of deep conditioning mask</li>
  <li>Final blow-dry and styling</li>
</ol>

<p>The entire process takes approximately 1.5-2 hours, depending on hair length and thickness. Results typically last 10-12 weeks with proper care.</p>

<blockquote>
  <p>"Brazilian Blowout has revolutionized hair smoothing treatments by delivering incredible results without damaging the hair or requiring extensive downtime."</p>
</blockquote>

<h3>Aftercare for Maximum Longevity</h3>
<p>To maintain your Brazilian Blowout results, we recommend using sodium chloride-free and sulfate-free products. Our salon offers the complete line of Brazilian Blowout aftercare products that are specially formulated to extend the life of your treatment and maintain the health of your hair.</p>',

                'description_ar' => '<h2>علاج تنعيم البرازيلي للشعر</h2>
<p>علاج البرازيلي الاحترافي لدينا هو الحل النهائي للشعر المجعد وغير القابل للتحكم. يخلق نظام التنعيم الثوري هذا طبقة واقية من البروتين حول كل خصلة شعر، مما يزيل التجعد ويوفر نعومة ولمعانًا رائعين.</p>

<h3>فوائد البرازيلي بلو آوت:</h3>
<ul>
  <li><strong>يزيل التجعد:</strong> ودع التجعد حتى في الظروف الرطبة.</li>
  <li><strong>يقلل وقت التصفيف:</strong> يقلل وقت التجفيف بنسبة تصل إلى 50%.</li>
  <li><strong>يحافظ على الحركة الطبيعية:</strong> على عكس بعض علاجات الكيراتين، يحافظ البرازيلي بلو آوت على جسم شعرك وحركته الطبيعية.</li>
  <li><strong>نتائج فورية:</strong> لا فترة انتظار - شعرك جاهز للغسل والتصفيف والاستمتاع مباشرة بعد العلاج.</li>
  <li><strong>نتائج قابلة للتخصيص:</strong> يمكن تخصيصها لاحتياجات شعرك المحددة من التنعيم الخفيف إلى التمليس الدراماتيكي.</li>
</ul>

<h3>عملية العلاج لدينا</h3>
<p>تشمل خدمة البرازيلي بلو آوت:</p>
<ol>
  <li>شامبو منظف لتحضير الشعر</li>
  <li>تطبيق محلول البرازيلي بلو آوت المهني</li>
  <li>تجفيف بالمجفف باستخدام تقنيات احترافية</li>
  <li>عملية المكواة المسطحة لختم العلاج</li>
  <li>شطف نهائي وتطبيق قناع ترطيب عميق</li>
  <li>تجفيف وتصفيف نهائي</li>
</ol>

<p>تستغرق العملية بأكملها حوالي 1.5-2 ساعة، اعتمادًا على طول الشعر وسماكته. تستمر النتائج عادة 10-12 أسبوعًا مع العناية المناسبة.</p>

<blockquote>
  <p>"لقد أحدث البرازيلي بلو آوت ثورة في علاجات تنعيم الشعر من خلال تقديم نتائج لا تصدق دون الإضرار بالشعر أو الحاجة إلى فترة تعافٍ طويلة."</p>
</blockquote>

<h3>العناية اللاحقة لأقصى عمر</h3>
<p>للحفاظ على نتائج البرازيلي بلو آوت، نوصي باستخدام منتجات خالية من كلوريد الصوديوم والكبريتات. يقدم صالوننا المجموعة الكاملة من منتجات العناية اللاحقة للبرازيلي بلو آوت المصممة خصيصًا لإطالة عمر العلاج والحفاظ على صحة شعرك.</p>',
                'price' => 180,
                'price_home' => 255,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 1,
                'duration_minutes' => 120,
                'image' => 'services/Brazilian Blow Out.jpg',
            ],
            [
                'category_name' => 'Hair Treatments',
                'point_of_sale_id' => 2,
                'name_en' => 'Keratin Complex Express',
                'name_ar' => 'علاج الكيراتين السريع',
                'description_en' => '<h2>Express Keratin Smoothing Treatment</h2>
<p>Our Keratin Complex Express treatment offers a quick solution for smoother, more manageable hair when you\'re short on time. This abbreviated version of our full keratin service reduces frizz and adds shine in just one hour.</p>
<p>Perfect for those new to keratin treatments or clients with fine to medium hair who desire improved texture and manageability without a lengthy salon visit.</p>',
                'description_ar' => '<h2>علاج تنعيم الكيراتين السريع</h2>
<p>يقدم علاج الكيراتين السريع لدينا حلاً سريعًا للحصول على شعر أكثر نعومة وسهولة في التحكم عندما يكون وقتك محدودًا. يقلل هذا الإصدار المختصر من خدمة الكيراتين الكاملة لدينا من التجعد ويضيف لمعانًا في ساعة واحدة فقط.</p>
<p>مثالي لأولئك الجدد في علاجات الكيراتين أو العملاء ذوي الشعر الرفيع إلى المتوسط الذين يرغبون في تحسين القوام وسهولة التحكم دون زيارة طويلة للصالون.</p>',
                'price' => 210,
                'price_home' => null,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => false,
                'sort_order' => 2,
                'duration_minutes' => 60,
                'image' => 'services/Keratin Complex Express.jpg',
            ],
            [
                'category_name' => 'Hair Treatments',
                'point_of_sale_id' => 2,
                'name_en' => 'Keratin Complex',
                'name_ar' => 'علاج الكيراتين',
                'description_en' => '<h2>Keratin Complex Smoothing Treatment</h2>
<p>Transform your unruly hair with our complete Keratin Complex smoothing treatment. This comprehensive service infuses keratin deep into the hair cuticle, eliminating up to 95% of frizz and significantly reducing styling time.</p>
<p>The results last up to 5 months, providing beautifully smooth, manageable hair that resists humidity and maintains its sleek appearance even in challenging weather conditions.</p>',
                'description_ar' => '<h2>علاج تنعيم الكيراتين الشامل</h2>
<p>حولي شعرك المتمرد مع علاج تنعيم الكيراتين الشامل لدينا. تضخ هذه الخدمة الشاملة الكيراتين بعمق في بشرة الشعر، مما يزيل ما يصل إلى 95% من التجعد ويقلل بشكل كبير من وقت التصفيف.</p>
<p>تستمر النتائج حتى 5 أشهر، مما يوفر شعرًا ناعمًا وقابلاً للتحكم بشكل جميل يقاوم الرطوبة ويحافظ على مظهره الأنيق حتى في ظروف الطقس الصعبة.</p>',
                'price' => 280,
                'price_home' => 420,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 3,
                'duration_minutes' => 120,
                'image' => 'services/Keratin Complex.jpg',
            ],
            [
                'category_name' => 'Hair Treatments',
                'point_of_sale_id' => 2,
                'name_en' => 'Keratin Complex Max',
                'name_ar' => 'علاج الكيراتين ماكس',
                'description_en' => '<h2>Maximum Strength Keratin Treatment</h2>
<p>Our Keratin Complex Max is the ultimate solution for extremely curly, coarse, or resistant hair types. This intensive treatment combines our strongest keratin formula with specialized KC MAX Treatment Spray for unparalleled smoothing power.</p>
<p>The extended processing time and enhanced formula ensure maximum frizz elimination and straightening effects that last longer than standard treatments, even in the most challenging hair conditions.</p>',
                'description_ar' => '<h2>علاج الكيراتين بأقصى قوة</h2>
<p>كيراتين كومبلكس ماكس هو الحل النهائي لأنواع الشعر شديدة التجعد أو الخشنة أو المقاومة. يجمع هذا العلاج المكثف بين أقوى تركيبة كيراتين لدينا ورذاذ علاج KC MAX المتخصص لقوة تنعيم لا مثيل لها.</p>
<p>يضمن وقت المعالجة الممتد والصيغة المحسنة أقصى قدر من القضاء على التجعد وتأثيرات التمليس التي تدوم لفترة أطول من العلاجات القياسية، حتى في أصعب حالات الشعر.</p>',
                'price' => 350,
                'price_home' => 495,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 4,
                'duration_minutes' => 150,
                'image' => 'services/Keratin Complex Max.jpg',
            ],
            [
                'category_name' => 'Hair Treatments',
                'point_of_sale_id' => 2,
                'name_en' => 'Permanent Wave',
                'name_ar' => 'تمويج دائم',
                'description_en' => '<h2>Professional Permanent Wave Service</h2>
<p>Create lasting curls and texture with our professional permanent wave service. Using advanced waving solutions and techniques, we customize the size and pattern of curls to suit your desired look and hair type.</p>
<p>Our expert stylists carefully process your hair to ensure even, bouncy curls that maintain their shape for months, adding volume and dimension to straight or limp hair.</p>',
                'description_ar' => '<h2>خدمة التمويج الدائم الاحترافية</h2>
<p>ابتكري تجعيدات وقوامًا دائمًا مع خدمة التمويج الدائم الاحترافية لدينا. باستخدام محاليل وتقنيات التمويج المتقدمة، نقوم بتخصيص حجم ونمط التجعيدات لتناسب المظهر المطلوب ونوع شعرك.</p>
<p>يعالج مصففونا الخبراء شعرك بعناية لضمان تجعيدات متساوية ومرنة تحافظ على شكلها لعدة أشهر، مما يضيف حجمًا وأبعادًا للشعر المستقيم أو الضعيف.</p>',
                'price' => 185,
                'price_home' => 240,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 5,
                'duration_minutes' => 120,
                'image' => 'services/Permanent Wave.jpg',
            ],
            [
                'category_name' => 'Hair Treatments',
                'point_of_sale_id' => 2,
                'name_en' => 'Hair Gloss',
                'name_ar' => 'لمعان الشعر',
                'description_en' => '<h2>Hair Gloss Treatment</h2>
<p>Enhance your hair\'s natural shine with our specialized hair gloss treatment. This quick service applies a clear or tinted gloss that fills in the hair cuticle, creating a smooth, reflective surface that radiates healthy brilliance.</p>
<p>Perfect as a standalone treatment or as a finishing touch after coloring services, our hair gloss revitalizes dull hair and extends the life of your color by sealing in the pigment and repelling environmental damage.</p>',
                'description_ar' => '<h2>علاج لمعان الشعر</h2>
<p>عززي اللمعان الطبيعي لشعرك مع علاج لمعان الشعر المتخصص لدينا. تطبق هذه الخدمة السريعة طبقة لامعة شفافة أو ملونة تملأ بشرة الشعر، مما يخلق سطحًا أملسًا عاكسًا يشع بتألق صحي.</p>
<p>مثالي كعلاج قائم بذاته أو كلمسة نهائية بعد خدمات التلوين، يجدد لمعان الشعر لدينا الشعر الباهت ويطيل عمر لونك من خلال إغلاق الصبغة وصد الأضرار البيئية.</p>',
                'price' => 40,
                'price_home' => 55,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 6,
                'duration_minutes' => 30,
                'image' => 'services/Hair Gloss.jpg',
            ],
            [
                'category_name' => 'Hair Treatments',
                'point_of_sale_id' => 2,
                'name_en' => 'Safe Color Treatment',
                'name_ar' => 'علاج تلوين آمن',
                'description_en' => '<h2>Safe Color Treatment</h2>
<p>Our gentle, low-chemical Safe Color Treatment provides beautiful results without harsh ingredients. Specially formulated for those with sensitivities, pregnant clients, or anyone preferring a more natural approach to hair coloring.</p>
<p>This treatment uses plant-based pigments and conditioning agents that nourish your hair while adding rich, dimensional color that fades gradually and naturally.</p>',
                'description_ar' => '<h2>علاج التلوين الآمن</h2>
<p>يوفر علاج التلوين الآمن اللطيف منخفض المواد الكيميائية لدينا نتائج جميلة دون مكونات قاسية. مصمم خصيصًا لأصحاب الحساسية، أو العملاء الحوامل، أو أي شخص يفضل نهجًا أكثر طبيعية لتلوين الشعر.</p>
<p>يستخدم هذا العلاج أصباغًا نباتية ومواد ترطيب تغذي شعرك مع إضافة لون غني وثلاثي الأبعاد يتلاشى تدريجيًا وبشكل طبيعي.</p>',
                'price' => 35,
                'price_home' => 95,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 7,
                'duration_minutes' => 60,
                'image' => 'services/Safe Color Treatment.jpg',
            ],
            [
                'category_name' => 'Hair Treatments',
                'point_of_sale_id' => 2,
                'name_en' => 'Hair & Scalp Treatments',
                'name_ar' => 'علاجات الشعر وفروة الرأس',
                'description_en' => '<h2>Comprehensive Hair & Scalp Treatment</h2>
<p>Address both hair and scalp concerns with our specialized dual-action treatment. This therapeutic service begins with a thorough scalp analysis to identify specific issues like dryness, excess oil, or irritation.</p>
<p>We then apply customized professional-grade products that nourish the scalp while simultaneously treating the hair shaft to improve overall hair health, strength, and appearance from root to tip.</p>',
                'description_ar' => '<h2>علاج شامل للشعر وفروة الرأس</h2>
<p>عالجي مشاكل الشعر وفروة الرأس معًا مع علاجنا المتخصص مزدوج التأثير. تبدأ هذه الخدمة العلاجية بتحليل شامل لفروة الرأس لتحديد المشاكل المحددة مثل الجفاف، الزيت الزائد، أو التهيج.</p>
<p>ثم نقوم بتطبيق منتجات متخصصة ذات مستوى احترافي تغذي فروة الرأس مع معالجة بنية الشعر في نفس الوقت لتحسين صحة الشعر العامة وقوته ومظهره من الجذور إلى الأطراف.</p>',
                'price' => 45,
                'price_home' => 240,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 8,
                'duration_minutes' => 60,
                'image' => 'services/Hair & Scalp Treatments.jpg',
            ],

            // Hair Extensions
            [
                'category_name' => 'Hair Extensions',
                'point_of_sale_id' => 2,
                'name_en' => 'Blowdry with Extensions',
                'name_ar' => 'تنشيف الشعر مع التمديد',
                'description_en' => '<h2>Blowdry for Hair Extensions</h2>
<p>Our specialized blowdry service is designed specifically for hair with extensions, using techniques and products that protect both your natural hair and extensions while creating beautiful, seamless styles.</p>
<p>Our stylists are trained in proper heat management and product application for all extension types to ensure longevity and prevent damage to your investment.</p>',
                'description_ar' => '<h2>تنشيف الشعر مع وصلات التمديد</h2>
<p>خدمة تنشيف الشعر المتخصصة لدينا مصممة خصيصًا للشعر مع وصلات التمديد، باستخدام تقنيات ومنتجات تحمي شعرك الطبيعي والوصلات معًا مع إنشاء تسريحات جميلة ومتناسقة.</p>
<p>مصففونا مدربون على إدارة الحرارة المناسبة وتطبيق المنتج لجميع أنواع وصلات التمديد لضمان طول العمر ومنع الضرر لاستثمارك.</p>',
                'price' => 65,
                'price_home' => 95,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 1,
                'duration_minutes' => 60,
                'image' => 'services/Blowdry with Extensions.jpg',
            ],
            [
                'category_name' => 'Hair Extensions',
                'point_of_sale_id' => 2,
                'name_en' => 'Extensions Service',
                'name_ar' => 'خدمة التمديد',
                'description_en' => '<h2>Professional Hair Extension Application</h2>
<p>Transform your hair with our expert extension application service. Our certified technicians skillfully apply premium quality hair extensions using methods chosen specifically for your hair type and desired result.</p>
<p>We offer various application techniques including tape-in, micro-link, sew-in, and clip-in options, all installed with precision to ensure comfort, natural appearance, and minimal stress on your natural hair.</p>',
                'description_ar' => '<h2>تطبيق وصلات الشعر الاحترافية</h2>
<p>غيري شعرك مع خدمة تطبيق وصلات الشعر المتخصصة لدينا. يقوم فنيونا المعتمدون بتطبيق وصلات شعر عالية الجودة بمهارة باستخدام طرق مختارة خصيصًا لنوع شعرك والنتيجة المرجوة.</p>
<p>نقدم تقنيات تطبيق متنوعة بما في ذلك وصلات الشريط اللاصق، والوصلات الدقيقة، والوصلات المخيطة، وخيارات الوصلات ذات المشابك، وكلها يتم تركيبها بدقة لضمان الراحة والمظهر الطبيعي والحد الأدنى من الضغط على شعرك الطبيعي.</p>',
                'price' => 110,
                'price_home' => null,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => false,
                'sort_order' => 2,
                'duration_minutes' => 60,
                'image' => 'services/Extensions Service.jpg',
            ],
            [
                'category_name' => 'Hair Extensions',
                'point_of_sale_id' => 2,
                'name_en' => 'Keratin Hair Extensions',
                'name_ar' => 'تمديد الشعر بالكيراتين',
                'description_en' => '<h2>Keratin Bonded Hair Extensions</h2>
<p>Our premium keratin hair extension service uses the innovative hot fusion method to create a secure, comfortable bond between extension and natural hair. These extensions lie flat against the scalp for a truly natural look and feel.</p>
<p>The keratin bonding technique is ideal for those seeking long-term extensions that can withstand various styling options, including heat tools and updos, while remaining undetectable and comfortable.</p>',
                'description_ar' => '<h2>وصلات الشعر المثبتة بالكيراتين</h2>
<p>تستخدم خدمة وصلات الشعر بالكيراتين المتميزة لدينا طريقة الدمج الساخن المبتكرة لإنشاء رابط آمن ومريح بين الوصلة والشعر الطبيعي. تستلقي هذه الوصلات بشكل مسطح على فروة الرأس للحصول على مظهر وملمس طبيعي حقًا.</p>
<p>تقنية الربط بالكيراتين مثالية لأولئك الذين يبحثون عن وصلات طويلة الأمد يمكنها تحمل خيارات التصفيف المختلفة، بما في ذلك أدوات الحرارة والتسريحات المرفوعة، مع البقاء غير مرئية ومريحة.</p>',
                'price' => 720,
                'price_home' => 860,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 3,
                'duration_minutes' => 180,
                'image' => 'services/Keratin Hair Extensions.jpg',
            ],
            [
                'category_name' => 'Hair Extensions',
                'point_of_sale_id' => 2,
                'name_en' => 'Hair Extension Removal',
                'name_ar' => 'إزالة تمديد الشعر',
                'description_en' => '<h2>Professional Extension Removal</h2>
<p>Our expert extension removal service safely and gently removes all types of hair extensions without damaging your natural hair. We use specialized tools and techniques specific to each extension method to ensure a comfortable experience.</p>
<p>The service includes a nourishing treatment to revitalize your natural hair after extension wear, leaving it healthy, hydrated, and ready for its next style transformation.</p>',
                'description_ar' => '<h2>إزالة الوصلات الاحترافية</h2>
<p>تقوم خدمة إزالة الوصلات المتخصصة لدينا بإزالة جميع أنواع وصلات الشعر بأمان ولطف دون الإضرار بشعرك الطبيعي. نستخدم أدوات وتقنيات متخصصة محددة لكل طريقة وصل لضمان تجربة مريحة.</p>
<p>تتضمن الخدمة علاجًا مغذيًا لإعادة تنشيط شعرك الطبيعي بعد ارتداء الوصلات، مما يتركه صحيًا ومرطبًا وجاهزًا لتحويل أسلوبه التالي.</p>',
                'price' => 85,
                'price_home' => 275,
                'is_active' => true,
                'is_product' => false,
                'can_be_done_at_home' => true,
                'sort_order' => 4,
                'duration_minutes' => 90,
                'image' => 'services/Hair Extension Removal.jpg',
            ],
        ];

        foreach ($services as $service) {
            $category = ServiceCategory::where('name_en', $service['category_name'])->first();
            if ($category) {
                unset($service['category_name']);
                $service['category_id'] = $category->id;
                ProductAndService::create($service);
            }
        }
    }
}
