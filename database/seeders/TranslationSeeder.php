<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get language IDs
        $englishLanguage = Language::where('code', 'en')->first();
        $arabicLanguage = Language::where('code', 'ar')->first();

        if (!$englishLanguage || !$arabicLanguage) {
            $this->command->info('Please run language seeder first.');
            return;
        }

        $translations = [
            'Email address is required' =>
            [
                'en' => 'Email address is required',
                'ar' => 'البريد الإلكتروني مطلوب',
                'group' => 'auth',
            ],
            'Please enter a valid email address' =>
            [
                'en' => 'Please enter a valid email address',
                'ar' => 'يرجى إدخال عنوان بريد إلكتروني صالح',
                'group' => 'auth',
            ],
            'Password is required' =>
            [
                'en' => 'Password is required',
                'ar' => 'كلمة المرور مطلوبة',
                'group' => 'auth',
            ],
            'Please verify your email address before logging in.' =>
            [
                'en' => 'Please verify your email address before logging in.',
                'ar' => 'يرجى التحقق من عنوان بريدك الإلكتروني قبل تسجيل الدخول.',
                'group' => 'auth',
            ],
            'The provided credentials do not match our records.' =>
            [
                'en' => 'The provided credentials do not match our records.',
                'ar' => 'بيانات الاعتماد المقدمة لا تتطابق مع سجلاتنا.',
                'group' => 'auth',
            ],
            'Name is required' =>
            [
                'en' => 'Name is required',
                'ar' => 'الاسم مطلوب',
                'group' => 'auth',
            ],
            'Name must not exceed 255 characters' =>
            [
                'en' => 'Name must not exceed 255 characters',
                'ar' => 'يجب ألا يتجاوز الاسم 255 حرفًا',
                'group' => 'auth',
            ],
            'Email must not exceed 255 characters' =>
            [
                'en' => 'Email must not exceed 255 characters',
                'ar' => 'يجب ألا يتجاوز البريد الإلكتروني 255 حرفًا',
                'group' => 'auth',
            ],
            'This email address is already in use' =>
            [
                'en' => 'This email address is already in use',
                'ar' => 'عنوان البريد الإلكتروني هذا قيد الاستخدام بالفعل',
                'group' => 'auth',
            ],
            'Phone number is required' =>
            [
                'en' => 'Phone number is required',
                'ar' => 'رقم الهاتف مطلوب',
                'group' => 'auth',
            ],
            'Phone number must not exceed 20 characters' =>
            [
                'en' => 'Phone number must not exceed 20 characters',
                'ar' => 'يجب ألا يتجاوز رقم الهاتف 20 حرفًا',
                'group' => 'auth',
            ],
            'Password must be at least 8 characters' =>
            [
                'en' => 'Password must be at least 8 characters',
                'ar' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل',
                'group' => 'auth',
            ],
            'Password confirmation does not match' =>
            [
                'en' => 'Password confirmation does not match',
                'ar' => 'تأكيد كلمة المرور غير متطابق',
                'group' => 'auth',
            ],
            'Please check your email for verification link.' =>
            [
                'en' => 'Please check your email for verification link.',
                'ar' => 'يرجى التحقق من بريدك الإلكتروني للحصول على رابط التحقق.',
                'group' => 'auth',
            ],
            'Registration failed. Please try again.' =>
            [
                'en' => 'Registration failed. Please try again.',
                'ar' => 'فشل التسجيل. يرجى المحاولة مرة أخرى.',
                'group' => 'auth',
            ],
            'Your password reset link has been sent to your email.' =>
            [
                'en' => 'Your password reset link has been sent to your email.',
                'ar' => 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.',
                'group' => 'auth',
            ],
            'Reset token is required' =>
            [
                'en' => 'Reset token is required',
                'ar' => 'رمز إعادة التعيين مطلوب',
                'group' => 'auth',
            ],
            'Password reset successfully.' =>
            [
                'en' => 'Password reset successfully.',
                'ar' => 'تم إعادة تعيين كلمة المرور بنجاح.',
                'group' => 'auth',
            ],
            'Verification token is missing.' =>
            [
                'en' => 'Verification token is missing.',
                'ar' => 'رمز التحقق مفقود.',
                'group' => 'auth',
            ],
            'Invalid verification token.' =>
            [
                'en' => 'Invalid verification token.',
                'ar' => 'رمز التحقق غير صالح.',
                'group' => 'auth',
            ],
            'The email address is already in use by another account.' =>
            [
                'en' => 'The email address is already in use by another account.',
                'ar' => 'عنوان البريد الإلكتروني قيد الاستخدام بالفعل من قبل حساب آخر.',
                'group' => 'auth',
            ],
            'Email verified successfully.' =>
            [
                'en' => 'Email verified successfully.',
                'ar' => 'تم التحقق من البريد الإلكتروني بنجاح.',
                'group' => 'auth',
            ],
            'Verification link sent!' =>
            [
                'en' => 'Verification link sent!',
                'ar' => 'تم إرسال رابط التحقق!',
                'group' => 'auth',
            ],
            'English name is required' =>
            [
                'en' => 'English name is required',
                'ar' => 'الاسم باللغة الإنجليزية مطلوب',
                'group' => 'auth',
            ],
            'English name must not exceed 255 characters' =>
            [
                'en' => 'English name must not exceed 255 characters',
                'ar' => 'يجب ألا يتجاوز الاسم باللغة الإنجليزية 255 حرفًا',
                'group' => 'auth',
            ],
            'Arabic name is required' =>
            [
                'en' => 'Arabic name is required',
                'ar' => 'الاسم باللغة العربية مطلوب',
                'group' => 'auth',
            ],
            'Arabic name must not exceed 255 characters' =>
            [
                'en' => 'Arabic name must not exceed 255 characters',
                'ar' => 'يجب ألا يتجاوز الاسم باللغة العربية 255 حرفًا',
                'group' => 'auth',
            ],
            'Address must not exceed 255 characters' =>
            [
                'en' => 'Address must not exceed 255 characters',
                'ar' => 'يجب ألا يتجاوز العنوان 255 حرفًا',
                'group' => 'auth',
            ],
            'A verification link has been sent to your new email address. Please click the link to complete the email change.' =>
            [
                'en' => 'A verification link has been sent to your new email address. Please click the link to complete the email change.',
                'ar' => 'تم إرسال رابط التحقق إلى عنوان بريدك الإلكتروني الجديد. يرجى النقر على الرابط لإكمال تغيير البريد الإلكتروني.',
                'group' => 'auth',
            ],
            'Profile updated successfully.' =>
            [
                'en' => 'Profile updated successfully.',
                'ar' => 'تم تحديث الملف الشخصي بنجاح.',
                'group' => 'auth',
            ],
            'Profile update failed. Please try again.' =>
            [
                'en' => 'Profile update failed. Please try again.',
                'ar' => 'فشل تحديث الملف الشخصي. يرجى المحاولة مرة أخرى.',
                'group' => 'auth',
            ],
            'Current password is required' =>
            [
                'en' => 'Current password is required',
                'ar' => 'كلمة المرور الحالية مطلوبة',
                'group' => 'auth',
            ],
            'New password is required' =>
            [
                'en' => 'New password is required',
                'ar' => 'كلمة المرور الجديدة مطلوبة',
                'group' => 'auth',
            ],
            'New password must be at least 8 characters' =>
            [
                'en' => 'New password must be at least 8 characters',
                'ar' => 'يجب أن تتكون كلمة المرور الجديدة من 8 أحرف على الأقل',
                'group' => 'auth',
            ],
            'New password confirmation does not match' =>
            [
                'en' => 'New password confirmation does not match',
                'ar' => 'تأكيد كلمة المرور الجديدة غير متطابق',
                'group' => 'auth',
            ],
            'The current password is incorrect.' =>
            [
                'en' => 'The current password is incorrect.',
                'ar' => 'كلمة المرور الحالية غير صحيحة.',
                'group' => 'auth',
            ],
            'Password updated successfully.' =>
            [
                'en' => 'Password updated successfully.',
                'ar' => 'تم تحديث كلمة المرور بنجاح.',
                'group' => 'auth',
            ],
            'Indulge Yourself' =>
            [
                'en' => 'Indulge Yourself',
                'ar' => 'دللي نفسك',
                'group' => 'home',
            ],
            'Making Your Look Awesome And Manly' =>
            [
                'en' => 'Making Your Look Awesome And Manly',
                'ar' => 'اختاري خدمتك باحدث التقنيات العصريه الحديثه',
                'group' => 'home',
            ],
            'Discover More' =>
            [
                'en' => 'Discover More',
                'ar' => 'اكتشف المزيد',
                'group' => 'home',
            ],
            'Declare Your Style' =>
            [
                'en' => 'Declare Your Style',
                'ar' => 'اعلني عن أسلوبك',
                'group' => 'home',
            ],
            'Feel Free To Express And Choose Your Style' =>
            [
                'en' => 'Feel Free To Express And Choose Your Style',
                'ar' => 'ولا تترددي في التعبير باختيار أسلوبك المتفرد',
                'group' => 'home',
            ],
            'View Salon Menu' =>
            [
                'en' => 'View Salon Menu',
                'ar' => 'عرض قائمة الصالون',
                'group' => 'home',
            ],
            'Shear. Shave. Shine' =>
            [
                'en' => 'Shear. Shave. Shine',
                'ar' => 'قص -سشوار -بروتين وخدمات اخرى',
                'group' => 'home',
            ],
            'Traditional Service In A Modern Manner' =>
            [
                'en' => 'Traditional Service In A Modern Manner',
                'ar' => 'خدمة تقليدية بطريقة عصرية',
                'group' => 'home',
            ],
            'Online Booking' =>
            [
                'en' => 'Online Booking',
                'ar' => 'حجز عبر الإنترنت',
                'group' => 'home',
            ],
            'Empower your look. Elevate your game' =>
            [
                'en' => 'Empower your look. Elevate your game',
                'ar' => 'عزز مظهرك. ارفع مستوى لعبتك',
                'group' => 'home',
            ],
            'Your Hair, Your Style' =>
            [
                'en' => 'Your Hair, Your Style',
                'ar' => 'شعرك، أسلوبك',
                'group' => 'home',
            ],
            'Services for progressive or traditional gentlemen' =>
            [
                'en' => 'Services for progressive or traditional gentlemen',
                'ar' => 'خدمات للرجال التقدميين أو التقليديين',
                'group' => 'home',
            ],
            'Don\'t be ordinary, be extraordinary' =>
            [
                'en' => 'Don\'t be ordinary, be extraordinary',
                'ar' => 'لا تكن عاديًا، كن استثنائيًا',
                'group' => 'home',
            ],
            'Haircut' =>
            [
                'en' => 'Haircut',
                'ar' => 'قصة شعر',
                'group' => 'home',
            ],
            'Moustache Trim' =>
            [
                'en' => 'Moustache Trim',
                'ar' => 'تشذيب الشارب',
                'group' => 'home',
            ],
            'Face Shave' =>
            [
                'en' => 'Face Shave',
                'ar' => 'حلاقة الوجه',
                'group' => 'home',
            ],
            'Beard Trim' =>
            [
                'en' => 'Beard Trim',
                'ar' => 'تشذيب اللحية',
                'group' => 'home',
            ],
            'Come, Relax and Enjoy' =>
            [
                'en' => 'Come, Relax and Enjoy',
                'ar' => 'تعال، استرخِ واستمتع',
                'group' => 'home',
            ],
            'Place where you will feel peaceful' =>
            [
                'en' => 'Place where you will feel peaceful',
                'ar' => 'مكان حيث ستشعر بالسلام',
                'group' => 'home',
            ],
            'Book an Appointment' =>
            [
                'en' => 'Book an Appointment',
                'ar' => 'احجز موعدًا',
                'group' => 'contact',
            ],
            'You\'ll Like It Here!' =>
            [
                'en' => 'You\'ll Like It Here!',
                'ar' => 'ستعجبك هنا!',
                'group' => 'home',
            ],
            'Our Services & Prices' =>
            [
                'en' => 'Our Services & Prices',
                'ar' => 'خدماتنا وأسعارنا',
                'group' => 'home',
            ],
            'Haircut & Style' =>
            [
                'en' => 'Haircut & Style',
                'ar' => 'قصة شعر وتصفيف',
                'group' => 'home',
            ],
            'Buzz Cut' =>
            [
                'en' => 'Buzz Cut',
                'ar' => 'قصة بز',
                'group' => 'home',
            ],
            'Straight Razor Shave' =>
            [
                'en' => 'Straight Razor Shave',
                'ar' => 'حلاقة بالشفرة المستقيمة',
                'group' => 'home',
            ],
            'Head Shave' =>
            [
                'en' => 'Head Shave',
                'ar' => 'حلاقة الرأس',
                'group' => 'home',
            ],
            'Kids Cuts (10 & under)' =>
            [
                'en' => 'Kids Cuts (10 & under)',
                'ar' => 'قصات الأطفال (10 سنوات وأقل)',
                'group' => 'home',
            ],
            'Back of Neck Razor Cleanup' =>
            [
                'en' => 'Back of Neck Razor Cleanup',
                'ar' => 'تنظيف مؤخرة الرقبة بالشفرة',
                'group' => 'home',
            ],
            'Luxury Face Shave' =>
            [
                'en' => 'Luxury Face Shave',
                'ar' => 'حلاقة وجه فاخرة',
                'group' => 'home',
            ],
            'Beard Trim with Razor' =>
            [
                'en' => 'Beard Trim with Razor',
                'ar' => 'تشذيب اللحية بالشفرة',
                'group' => 'home',
            ],
            'Line Up/Beard Trim' =>
            [
                'en' => 'Line Up/Beard Trim',
                'ar' => 'ترتيب الخطوط/تشذيب اللحية',
                'group' => 'home',
            ],
            'Signature Bespoke Facial' =>
            [
                'en' => 'Signature Bespoke Facial',
                'ar' => 'علاج الوجه المخصص الخاص',
                'group' => 'home',
            ],
            'Ear & Nose Waxing' =>
            [
                'en' => 'Ear & Nose Waxing',
                'ar' => 'إزالة شعر الأذن والأنف بالشمع',
                'group' => 'home',
            ],
            'Weddings Packages' =>
            [
                'en' => 'Weddings Packages',
                'ar' => 'باقات الزفاف',
                'group' => 'home',
            ],
            'View All Prices' =>
            [
                'en' => 'View All Prices',
                'ar' => 'عرض جميع الأسعار',
                'group' => 'home',
            ],
            'Try Different Things' =>
            [
                'en' => 'Try Different Things',
                'ar' => 'جرب أشياء مختلفة',
                'group' => 'home',
            ],
            'Are you ready to make a big change?' =>
            [
                'en' => 'Are you ready to make a big change?',
                'ar' => 'هل أنت مستعد لإجراء تغيير كبير؟',
                'group' => 'home',
            ],
            'Time Schedule' =>
            [
                'en' => 'Time Schedule',
                'ar' => 'جدول المواعيد',
                'group' => 'home',
            ],
            'Working Hours' =>
            [
                'en' => 'Working Hours',
                'ar' => 'ساعات العمل',
                'group' => 'staff',
            ],
            'Time intervals' =>
            [
                'en' => 'Time intervals',
                'ar' => 'فترات الوقت',
                'group' => 'staff',
            ],
            'Time Interval' =>
            [
                'en' => 'Time Interval',
                'ar' => 'فترة زمنية',
                'group' => 'staff',
            ],
            'Time Intervals' =>
            [
                'en' => 'Time Intervals',
                'ar' => 'الفترات الزمنية',
                'group' => 'staff',
            ],
            'Opening Time' =>
            [
                'en' => 'Opening Time',
                'ar' => 'وقت الفتح',
                'group' => 'staff',
            ],
            'Closing Time' =>
            [
                'en' => 'Closing Time',
                'ar' => 'وقت الإغلاق',
                'group' => 'staff',
            ],
            'Closed' =>
            [
                'en' => 'Closed',
                'ar' => 'مغلق',
                'group' => 'staff',
            ],
            'Closed Days' =>
            [
                'en' => 'Closed Days',
                'ar' => 'أيام الإغلاق',
                'group' => 'staff',
            ],
            'Testimonials' =>
            [
                'en' => 'Testimonials',
                'ar' => 'آراء العملاء',
                'group' => 'navigation',
            ],
            'Comments & Reviews' =>
            [
                'en' => 'Comments & Reviews',
                'ar' => 'التعليقات والمراجعات',
                'group' => 'home',
            ],
            'Salon Hours:' =>
            [
                'en' => 'Salon Hours:',
                'ar' => 'ساعات الصالون:',
                'group' => 'contact',
            ],
            'Our Location:' =>
            [
                'en' => 'Our Location:',
                'ar' => 'موقعنا:',
                'group' => 'contact',
            ],
            'mcs.sa - Home' =>
            [
                'en' => 'mcs.sa - Home',
                'ar' => 'رين - الرئيسية',
                'group' => 'home',
            ],
            'Monday' =>
            [
                'en' => 'Monday',
                'ar' => 'الاثنين',
                'group' => 'work-with-us',
            ],
            'Tuesday' =>
            [
                'en' => 'Tuesday',
                'ar' => 'الثلاثاء',
                'group' => 'work-with-us',
            ],
            'Wednesday' =>
            [
                'en' => 'Wednesday',
                'ar' => 'الأربعاء',
                'group' => 'work-with-us',
            ],
            'Thursday' =>
            [
                'en' => 'Thursday',
                'ar' => 'الخميس',
                'group' => 'work-with-us',
            ],
            'Friday' =>
            [
                'en' => 'Friday',
                'ar' => 'الجمعة',
                'group' => 'work-with-us',
            ],
            'Saturday' =>
            [
                'en' => 'Saturday',
                'ar' => 'السبت',
                'group' => 'work-with-us',
            ],
            'Sunday' =>
            [
                'en' => 'Sunday',
                'ar' => 'الأحد',
                'group' => 'work-with-us',
            ],
            'Mon – Wed' =>
            [
                'en' => 'Mon – Wed',
                'ar' => 'الإثنين - الأربعاء',
                'group' => 'home',
            ],
            'Sun - Sun' =>
            [
                'en' => 'Sun - Sun',
                'ar' => 'الأحد - الأحد',
                'group' => 'home',
            ],
            'Staff' =>
            [
                'en' => 'Staff',
                'ar' => 'الموظف',
                'group' => 'cart',
            ],
            'Staff Information' =>
            [
                'en' => 'Staff Information',
                'ar' => 'معلومات الموظف',
                'group' => 'staff',
            ],
            'Name' =>
            [
                'en' => 'Name',
                'ar' => 'الاسم',
                'group' => 'staff',
            ],
            'Position' =>
            [
                'en' => 'Position',
                'ar' => 'المنصب',
                'group' => 'staff',
            ],
            'Email' =>
            [
                'en' => 'Email',
                'ar' => 'البريد الإلكتروني',
                'group' => 'staff',
            ],
            'Phone Number' =>
            [
                'en' => 'Phone Number',
                'ar' => 'رقم الهاتف',
                'group' => 'staff',
            ],
            'Address' =>
            [
                'en' => 'Address',
                'ar' => 'العنوان',
                'group' => 'work-with-us',
            ],
            'Status' =>
            [
                'en' => 'Status',
                'ar' => 'الحالة',
                'group' => 'general',
            ],
            'Active' =>
            [
                'en' => 'Active',
                'ar' => 'نشط',
                'group' => 'general',
            ],
            'Inactive' =>
            [
                'en' => 'Inactive',
                'ar' => 'غير نشط',
                'group' => 'staff',
            ],
            'Assignments' =>
            [
                'en' => 'Assignments',
                'ar' => 'التعيينات',
                'group' => 'staff',
            ],
            'Point of Sale' =>
            [
                'en' => 'Point of Sale',
                'ar' => 'نقطة البيع',
                'group' => 'point_of_sale',
            ],
            'Off on this day' =>
            [
                'en' => 'Off on this day',
                'ar' => 'عطلة في هذا اليوم',
                'group' => 'staff',
            ],
            'Date' =>
            [
                'en' => 'Date',
                'ar' => 'التاريخ',
                'group' => 'cart',
            ],
            'Day of Week' =>
            [
                'en' => 'Day of Week',
                'ar' => 'يوم الأسبوع',
                'group' => 'staff',
            ],
            'Start Time' =>
            [
                'en' => 'Start Time',
                'ar' => 'وقت البدء',
                'group' => 'cart',
            ],
            'End Time' =>
            [
                'en' => 'End Time',
                'ar' => 'وقت الانتهاء',
                'group' => 'cart',
            ],
            'Created At' =>
            [
                'en' => 'Created At',
                'ar' => 'تم الإنشاء في',
                'group' => 'staff',
            ],
            'Updated At' =>
            [
                'en' => 'Updated At',
                'ar' => 'تم التحديث في',
                'group' => 'staff',
            ],
            'Access Denied' =>
            [
                'en' => 'Access Denied',
                'ar' => 'تم رفض الوصول',
                'group' => 'notifications',
            ],
            'You do not have permission to access this staff member.' =>
            [
                'en' => 'You do not have permission to access this staff member.',
                'ar' => 'ليس لديك إذن للوصول إلى هذا الموظف.',
                'group' => 'notifications',
            ],
            'You do not have permission to delete this staff member.' =>
            [
                'en' => 'You do not have permission to delete this staff member.',
                'ar' => 'ليس لديك إذن لحذف هذا الموظف.',
                'group' => 'notifications',
            ],
            'Create' =>
            [
                'en' => 'Create',
                'ar' => 'إنشاء',
                'group' => 'actions',
            ],
            'Edit' =>
            [
                'en' => 'Edit',
                'ar' => 'تعديل',
                'group' => 'actions',
            ],
            'Delete' =>
            [
                'en' => 'Delete',
                'ar' => 'حذف',
                'group' => 'actions',
            ],
            'Save' =>
            [
                'en' => 'Save',
                'ar' => 'حفظ',
                'group' => 'actions',
            ],
            'Cancel' =>
            [
                'en' => 'Cancel',
                'ar' => 'إلغاء',
                'group' => 'cart',
            ],
            'Search' =>
            [
                'en' => 'Search',
                'ar' => 'بحث',
                'group' => 'actions',
            ],
            'Filter' =>
            [
                'en' => 'Filter',
                'ar' => 'تصفية',
                'group' => 'actions',
            ],
            'Reset' =>
            [
                'en' => 'Reset',
                'ar' => 'إعادة تعيين',
                'group' => 'actions',
            ],
            'Submit' =>
            [
                'en' => 'Submit',
                'ar' => 'إرسال',
                'group' => 'actions',
            ],
            'Confirm' =>
            [
                'en' => 'Confirm',
                'ar' => 'تأكيد',
                'group' => 'cart',
            ],
            'Back' =>
            [
                'en' => 'Back',
                'ar' => 'رجوع',
                'group' => 'actions',
            ],
            'Next' =>
            [
                'en' => 'Next',
                'ar' => 'التالي',
                'group' => 'actions',
            ],
            'Previous' =>
            [
                'en' => 'Previous',
                'ar' => 'السابق',
                'group' => 'actions',
            ],
            'Yes' =>
            [
                'en' => 'Yes',
                'ar' => 'نعم',
                'group' => 'actions',
            ],
            'No' =>
            [
                'en' => 'No',
                'ar' => 'لا',
                'group' => 'actions',
            ],
            'Service Category' =>
            [
                'en' => 'Service Category',
                'ar' => 'فئة الخدمة',
                'group' => 'services',
            ],
            'Service Categories' =>
            [
                'en' => 'Service Categories',
                'ar' => 'فئات الخدمات',
                'group' => 'services',
            ],
            'Products and Services' =>
            [
                'en' => 'Products and Services',
                'ar' => 'المنتجات والخدمات',
                'group' => 'work-with-us',
            ],
            'English' =>
            [
                'en' => 'English',
                'ar' => 'الإنجليزية',
                'group' => 'general',
            ],
            'Arabic' =>
            [
                'en' => 'Arabic',
                'ar' => 'العربية',
                'group' => 'general',
            ],
            'Name (English)' =>
            [
                'en' => 'Name (English)',
                'ar' => 'الاسم (بالإنجليزية)',
                'group' => 'services',
            ],
            'Name (Arabic)' =>
            [
                'en' => 'Name (Arabic)',
                'ar' => 'الاسم (بالعربية)',
                'group' => 'general',
            ],
            'Description (English)' =>
            [
                'en' => 'Description (English)',
                'ar' => 'الوصف (بالإنجليزية)',
                'group' => 'services',
            ],
            'Description (Arabic)' =>
            [
                'en' => 'Description (Arabic)',
                'ar' => 'الوصف (بالعربية)',
                'group' => 'services',
            ],
            'Settings' =>
            [
                'en' => 'Settings',
                'ar' => 'الإعدادات',
                'group' => 'general',
            ],
            'Sort Order' =>
            [
                'en' => 'Sort Order',
                'ar' => 'ترتيب الفرز',
                'group' => 'general',
            ],
            'You do not have permission to access this service category.' =>
            [
                'en' => 'You do not have permission to access this service category.',
                'ar' => 'ليس لديك إذن للوصول إلى فئة الخدمة هذه.',
                'group' => 'notifications',
            ],
            'Product and Service' =>
            [
                'en' => 'Product and Service',
                'ar' => 'المنتج والخدمة',
                'group' => 'services',
            ],
            'Category' =>
            [
                'en' => 'Category',
                'ar' => 'الفئة',
                'group' => 'services',
            ],
            'Image' =>
            [
                'en' => 'Image',
                'ar' => 'الصورة',
                'group' => 'general',
            ],
            'Basic Information' =>
            [
                'en' => 'Basic Information',
                'ar' => 'المعلومات الأساسية',
                'group' => 'general',
            ],
            'Duration (minutes)' =>
            [
                'en' => 'Duration (minutes)',
                'ar' => 'المدة (بالدقائق)',
                'group' => 'services',
            ],
            'Leave empty if not applicable' =>
            [
                'en' => 'Leave empty if not applicable',
                'ar' => 'اتركه فارغًا إذا كان غير منطبق',
                'group' => 'general',
            ],
            'Product and Service Type' =>
            [
                'en' => 'Product and Service Type',
                'ar' => 'نوع المنتج والخدمة',
                'group' => 'services',
            ],
            'Is this a product?' =>
            [
                'en' => 'Is this a product?',
                'ar' => 'هل هذا منتج؟',
                'group' => 'services',
            ],
            'Can be done at home?' =>
            [
                'en' => 'Can be done at home?',
                'ar' => 'هل يمكن القيام به في المنزل؟',
                'group' => 'services',
            ],
            'Pricing' =>
            [
                'en' => 'Pricing',
                'ar' => 'التسعير',
                'group' => 'services',
            ],
            'Price' =>
            [
                'en' => 'Price',
                'ar' => 'السعر',
                'group' => 'cart',
            ],
            'Price at Salon' =>
            [
                'en' => 'Price at Salon',
                'ar' => 'السعر في الصالون',
                'group' => 'services',
            ],
            'Price at Home' =>
            [
                'en' => 'Price at Home',
                'ar' => 'السعر في المنزل',
                'group' => 'services',
            ],
            'Delivery Price' =>
            [
                'en' => 'Delivery Price',
                'ar' => 'سعر التوصيل',
                'group' => 'services',
            ],
            'Home Price' =>
            [
                'en' => 'Home Price',
                'ar' => 'سعر المنزل',
                'group' => 'services',
            ],
            'Product' =>
            [
                'en' => 'Product',
                'ar' => 'منتج',
                'group' => 'services',
            ],
            'Home Service' =>
            [
                'en' => 'Home Service',
                'ar' => 'خدمة منزلية',
                'group' => 'services',
            ],
            'You do not have permission to access this product or service.' =>
            [
                'en' => 'You do not have permission to access this product or service.',
                'ar' => 'ليس لديك إذن للوصول إلى هذا المنتج أو الخدمة.',
                'group' => 'notifications',
            ],
            'Setting' =>
            [
                'en' => 'Setting',
                'ar' => 'إعداد',
                'group' => 'settings',
            ],
            'Other Settings' =>
            [
                'en' => 'Other Settings',
                'ar' => 'إعدادات أخرى',
                'group' => 'settings',
            ],
            'Key' =>
            [
                'en' => 'Key',
                'ar' => 'المفتاح',
                'group' => 'settings',
            ],
            'Value' =>
            [
                'en' => 'Value',
                'ar' => 'القيمة',
                'group' => 'settings',
            ],
            'Reservation Setting' =>
            [
                'en' => 'Reservation Setting',
                'ar' => 'إعداد الحجز',
                'group' => 'reservations',
            ],
            'Reservation Settings' =>
            [
                'en' => 'Reservation Settings',
                'ar' => 'إعدادات الحجز',
                'group' => 'reservations',
            ],
            'Reservations' =>
            [
                'en' => 'Reservations',
                'ar' => 'الحجوزات',
                'group' => 'reservations',
            ],
            'Schedule Settings' =>
            [
                'en' => 'Schedule Settings',
                'ar' => 'إعدادات الجدول',
                'group' => 'reservations',
            ],
            'Salon closed on this day' =>
            [
                'en' => 'Salon closed on this day',
                'ar' => 'الصالون مغلق في هذا اليوم',
                'group' => 'reservations',
            ],
            'Select Staff' =>
            [
                'en' => 'Select Staff',
                'ar' => 'اختر الموظفين',
                'group' => 'reservations',
            ],
            'Same as Last Week' =>
            [
                'en' => 'Same as Last Week',
                'ar' => 'نفس الأسبوع الماضي',
                'group' => 'reservations',
            ],
            'Workers Count' =>
            [
                'en' => 'Workers Count',
                'ar' => 'عدد العمال',
                'group' => 'reservations',
            ],
            'Is Open' =>
            [
                'en' => 'Is Open',
                'ar' => 'مفتوح',
                'group' => 'reservations',
            ],
            'Upcoming Dates' =>
            [
                'en' => 'Upcoming Dates',
                'ar' => 'التواريخ القادمة',
                'group' => 'reservations',
            ],
            'Past Dates' =>
            [
                'en' => 'Past Dates',
                'ar' => 'التواريخ السابقة',
                'group' => 'reservations',
            ],
            'A reservation setting for this date already exists for this point of sale.' =>
            [
                'en' => 'A reservation setting for this date already exists for this point of sale.',
                'ar' => 'يوجد بالفعل إعداد حجز لهذا التاريخ لنقطة البيع هذه.',
                'group' => 'notifications',
            ],
            'You do not have permission to access this reservation setting.' =>
            [
                'en' => 'You do not have permission to access this reservation setting.',
                'ar' => 'ليس لديك إذن للوصول إلى إعداد الحجز هذا.',
                'group' => 'notifications',
            ],
            'Booked Reservation' =>
            [
                'en' => 'Booked Reservation',
                'ar' => 'حجز محجوز',
                'group' => 'reservations',
            ],
            'Booked Reservations' =>
            [
                'en' => 'Booked Reservations',
                'ar' => 'الحجوزات المحجوزة',
                'group' => 'reservations',
            ],
            'Reservation Details' =>
            [
                'en' => 'Reservation Details',
                'ar' => 'تفاصيل الحجز',
                'group' => 'reservations',
            ],
            'Customer' =>
            [
                'en' => 'Customer',
                'ar' => 'العميل',
                'group' => 'reservations',
            ],
            'Reservation Date' =>
            [
                'en' => 'Reservation Date',
                'ar' => 'تاريخ الحجز',
                'group' => 'reservations',
            ],
            'Duration' =>
            [
                'en' => 'Duration',
                'ar' => 'المدة',
                'group' => 'reservations',
            ],
            'min' =>
            [
                'en' => 'min',
                'ar' => 'دقيقة',
                'group' => 'general',
            ],
            'Location' =>
            [
                'en' => 'Location',
                'ar' => 'الموقع',
                'group' => 'reservations',
            ],
            'Pending' =>
            [
                'en' => 'Pending',
                'ar' => 'قيد الانتظار',
                'group' => 'reservations',
            ],
            'Confirmed' =>
            [
                'en' => 'Confirmed',
                'ar' => 'مؤكد',
                'group' => 'reservations',
            ],
            'Completed' =>
            [
                'en' => 'Completed',
                'ar' => 'مكتمل',
                'group' => 'reservations',
            ],
            'Cancelled' =>
            [
                'en' => 'Cancelled',
                'ar' => 'ملغي',
                'group' => 'reservations',
            ],
            'At Salon' =>
            [
                'en' => 'At Salon',
                'ar' => 'في الصالون',
                'group' => 'reservations',
            ],
            'At Home' =>
            [
                'en' => 'At Home',
                'ar' => 'في المنزل',
                'group' => 'reservations',
            ],
            'Total' =>
            [
                'en' => 'Total',
                'ar' => 'المجموع',
                'group' => 'cart',
            ],
            'Payment Information' =>
            [
                'en' => 'Payment Information',
                'ar' => 'معلومات الدفع',
                'group' => 'booking',
            ],
            'Subtotal' =>
            [
                'en' => 'Subtotal',
                'ar' => 'المجموع الفرعي',
                'group' => 'reservations',
            ],
            'VAT' =>
            [
                'en' => 'VAT',
                'ar' => 'ضريبة القيمة المضافة',
                'group' => 'reservations',
            ],
            'Discount' =>
            [
                'en' => 'Discount',
                'ar' => 'الخصم',
                'group' => 'reservations',
            ],
            'Total Price' =>
            [
                'en' => 'Total Price',
                'ar' => 'السعر الإجمالي',
                'group' => 'reservations',
            ],
            'Total Paid (Cash)' =>
            [
                'en' => 'Total Paid (Cash)',
                'ar' => 'إجمالي المدفوع (نقداً)',
                'group' => 'reservations',
            ],
            'Total Paid (Online)' =>
            [
                'en' => 'Total Paid (Online)',
                'ar' => 'إجمالي المدفوع (عبر الإنترنت)',
                'group' => 'reservations',
            ],
            'Notes' =>
            [
                'en' => 'Notes',
                'ar' => 'ملاحظات',
                'group' => 'general',
            ],
            'From' =>
            [
                'en' => 'From',
                'ar' => 'من',
                'group' => 'general',
            ],
            'Until' =>
            [
                'en' => 'Until',
                'ar' => 'حتى',
                'group' => 'general',
            ],
            'View' =>
            [
                'en' => 'View',
                'ar' => 'عرض',
                'group' => 'actions',
            ],
            'Point of Sales' =>
            [
                'en' => 'Point of Sales',
                'ar' => 'نقاط البيع',
                'group' => 'point_of_sale',
            ],
            'City' =>
            [
                'en' => 'City',
                'ar' => 'المدينة',
                'group' => 'general',
            ],
            'Website' =>
            [
                'en' => 'Website',
                'ar' => 'الموقع الإلكتروني',
                'group' => 'general',
            ],
            'Postal Code' =>
            [
                'en' => 'Postal Code',
                'ar' => 'الرمز البريدي',
                'group' => 'checkout',
            ],
            'Location Map' =>
            [
                'en' => 'Location Map',
                'ar' => 'خريطة الموقع',
                'group' => 'point_of_sale',
            ],
            'Latitude' =>
            [
                'en' => 'Latitude',
                'ar' => 'خط العرض',
                'group' => 'general',
            ],
            'Longitude' =>
            [
                'en' => 'Longitude',
                'ar' => 'خط الطول',
                'group' => 'general',
            ],
            'User Account' =>
            [
                'en' => 'User Account',
                'ar' => 'حساب المستخدم',
                'group' => 'point_of_sale',
            ],
            'Login Email' =>
            [
                'en' => 'Login Email',
                'ar' => 'البريد الإلكتروني للدخول',
                'group' => 'point_of_sale',
            ],
            'Password' =>
            [
                'en' => 'Password',
                'ar' => 'كلمة المرور',
                'group' => 'general',
            ],
            'Language' =>
            [
                'en' => 'Language',
                'ar' => 'اللغة',
                'group' => 'languages',
            ],
            'Languages' =>
            [
                'en' => 'Languages',
                'ar' => 'اللغات',
                'group' => 'languages',
            ],
            'Code' =>
            [
                'en' => 'Code',
                'ar' => 'الرمز',
                'group' => 'languages',
            ],
            'Native Name' =>
            [
                'en' => 'Native Name',
                'ar' => 'الاسم الأصلي',
                'group' => 'languages',
            ],
            'Show in Navbar' =>
            [
                'en' => 'Show in Navbar',
                'ar' => 'إظهار في شريط التنقل',
                'group' => 'languages',
            ],
            'Translations Management' =>
            [
                'en' => 'Translations Management',
                'ar' => 'إدارة الترجمات',
                'group' => 'languages',
            ],
            'Filter by Group' =>
            [
                'en' => 'Filter by Group',
                'ar' => 'تصفية حسب المجموعة',
                'group' => 'languages',
            ],
            'All Groups' =>
            [
                'en' => 'All Groups',
                'ar' => 'جميع المجموعات',
                'group' => 'languages',
            ],
            'Search Keys' =>
            [
                'en' => 'Search Keys',
                'ar' => 'البحث عن المفاتيح',
                'group' => 'languages',
            ],
            'Search...' =>
            [
                'en' => 'Search...',
                'ar' => 'بحث...',
                'group' => 'languages',
            ],
            'Add New Translation' =>
            [
                'en' => 'Add New Translation',
                'ar' => 'إضافة ترجمة جديدة',
                'group' => 'languages',
            ],
            'No translations found' =>
            [
                'en' => 'No translations found',
                'ar' => 'لم يتم العثور على ترجمات',
                'group' => 'languages',
            ],
            'Are you sure you want to delete this translation?' =>
            [
                'en' => 'Are you sure you want to delete this translation?',
                'ar' => 'هل أنت متأكد أنك تريد حذف هذه الترجمة؟',
                'group' => 'languages',
            ],
            'Key Name' =>
            [
                'en' => 'Key Name',
                'ar' => 'اسم المفتاح',
                'group' => 'languages',
            ],
            'Enter translation key' =>
            [
                'en' => 'Enter translation key',
                'ar' => 'أدخل مفتاح الترجمة',
                'group' => 'languages',
            ],
            'Enter translation for :language' =>
            [
                'en' => 'Enter translation for :language',
                'ar' => 'أدخل الترجمة للغة :language',
                'group' => 'languages',
            ],
            'View Translations' =>
            [
                'en' => 'View Translations',
                'ar' => 'عرض الترجمات',
                'group' => 'languages',
            ],
            'Follow:' =>
            [
                'en' => 'Follow:',
                'ar' => 'تابعنا:',
                'group' => 'common',
            ],
            'gallery-image' =>
            [
                'en' => 'gallery-image',
                'ar' => 'صورة المعرض',
                'group' => 'common',
            ],
            'Services - mcs.sa Salon' =>
            [
                'en' => 'Services - mcs.sa Salon',
                'ar' => 'الخدمات - صالون رين',
                'group' => 'services',
            ],
            'Book an Service' =>
            [
                'en' => 'Book an Service',
                'ar' => 'احجز خدمة',
                'group' => 'services',
            ],
            'Select services and products for your appointment' =>
            [
                'en' => 'Select services and products for your appointment',
                'ar' => 'اختر الخدمات والمنتجات لموعدك',
                'group' => 'services',
            ],
            'Categories' =>
            [
                'en' => 'Categories',
                'ar' => 'الفئات',
                'group' => 'services',
            ],
            'All Services' =>
            [
                'en' => 'All Services',
                'ar' => 'جميع الخدمات',
                'group' => 'services',
            ],
            'Search services...' =>
            [
                'en' => 'Search services...',
                'ar' => 'البحث عن الخدمات...',
                'group' => 'services',
            ],
            'No services found matching your criteria.' =>
            [
                'en' => 'No services found matching your criteria.',
                'ar' => 'لم يتم العثور على خدمات تطابق معاييرك.',
                'group' => 'services',
            ],
            'FAQs - mcs.sa Salon' =>
            [
                'en' => 'FAQs - mcs.sa Salon',
                'ar' => 'الأسئلة الشائعة - صالون رين',
                'group' => 'faq',
            ],
            'Questions & Answers' =>
            [
                'en' => 'Questions & Answers',
                'ar' => 'أسئلة وأجوبة',
                'group' => 'faq',
            ],
            'You have questions? We have answers.' =>
            [
                'en' => 'You have questions? We have answers.',
                'ar' => 'لديك أسئلة؟ لدينا إجابات.',
                'group' => 'faq',
            ],
            'How do I schedule an appointment?' =>
            [
                'en' => 'How do I schedule an appointment?',
                'ar' => 'كيف أحدد موعدًا؟',
                'group' => 'faq',
            ],
            'Who should I choose as my stylist?' =>
            [
                'en' => 'Who should I choose as my stylist?',
                'ar' => 'من يجب أن أختار كمصفف شعري؟',
                'group' => 'faq',
            ],
            'If I need to cancel my appointment, what should I do?' =>
            [
                'en' => 'If I need to cancel my appointment, what should I do?',
                'ar' => 'إذا احتجت إلى إلغاء موعدي، ماذا يجب أن أفعل؟',
                'group' => 'faq',
            ],
            'Do you take coupons, gift certificates, or gift cards?' =>
            [
                'en' => 'Do you take coupons, gift certificates, or gift cards?',
                'ar' => 'هل تقبلون القسائم، شهادات الهدايا، أو بطاقات الهدايا؟',
                'group' => 'faq',
            ],
            'What brand of products do you carry?' =>
            [
                'en' => 'What brand of products do you carry?',
                'ar' => 'ما هي العلامات التجارية للمنتجات التي تقدمونها؟',
                'group' => 'faq',
            ],
            'Still Have Questions?' =>
            [
                'en' => 'Still Have Questions?',
                'ar' => 'هل لديك المزيد من الأسئلة؟',
                'group' => 'faq',
            ],
            'Shopping Cart - mcs.sa Salon' =>
            [
                'en' => 'Shopping Cart - mcs.sa Salon',
                'ar' => 'عربة التسوق - صالون رين',
                'group' => 'cart',
            ],
            'Service Delivery Location' =>
            [
                'en' => 'Service Delivery Location',
                'ar' => 'موقع تقديم الخدمة',
                'group' => 'cart',
            ],
            'Choose where you would like to receive your services' =>
            [
                'en' => 'Choose where you would like to receive your services',
                'ar' => 'اختر المكان الذي ترغب في تلقي خدماتك فيه',
                'group' => 'cart',
            ],
            'At Our Salon' =>
            [
                'en' => 'At Our Salon',
                'ar' => 'في صالوننا',
                'group' => 'cart',
            ],
            'At Your Home' =>
            [
                'en' => 'At Your Home',
                'ar' => 'في منزلك',
                'group' => 'cart',
            ],
            'Services will be performed at our salon' =>
            [
                'en' => 'Services will be performed at our salon',
                'ar' => 'سيتم تقديم الخدمات في صالوننا',
                'group' => 'cart',
            ],
            'Services will be performed at your home (additional charges may apply)' =>
            [
                'en' => 'Services will be performed at your home (additional charges may apply)',
                'ar' => 'سيتم تقديم الخدمات في منزلك (قد تطبق رسوم إضافية)',
                'group' => 'cart',
            ],
            'Some services in your cart are not available for home service and will be removed.' =>
            [
                'en' => 'Some services in your cart are not available for home service and will be removed.',
                'ar' => 'بعض الخدمات في سلتك غير متوفرة للخدمة المنزلية وسيتم إزالتها.',
                'group' => 'cart',
            ],
            'Cart Items' =>
            [
                'en' => 'Cart Items',
                'ar' => 'عناصر السلة',
                'group' => 'cart',
            ],
            'Your cart is empty' =>
            [
                'en' => 'Your cart is empty',
                'ar' => 'سلتك فارغة',
                'group' => 'cart',
            ],
            'Browse our services and add items to your cart' =>
            [
                'en' => 'Browse our services and add items to your cart',
                'ar' => 'تصفح خدماتنا وأضف العناصر إلى سلتك',
                'group' => 'cart',
            ],
            'Browse Services' =>
            [
                'en' => 'Browse Services',
                'ar' => 'تصفح الخدمات',
                'group' => 'cart',
            ],
            'Service' =>
            [
                'en' => 'Service',
                'ar' => 'خدمة',
                'group' => 'booking',
            ],
            'Quantity' =>
            [
                'en' => 'Quantity',
                'ar' => 'الكمية',
                'group' => 'cart',
            ],
            'Continue Shopping' =>
            [
                'en' => 'Continue Shopping',
                'ar' => 'مواصلة التسوق',
                'group' => 'cart',
            ],
            'Clear Cart' =>
            [
                'en' => 'Clear Cart',
                'ar' => 'مسح السلة',
                'group' => 'cart',
            ],
            'Cart Summary' =>
            [
                'en' => 'Cart Summary',
                'ar' => 'ملخص السلة',
                'group' => 'cart',
            ],
            'Salon Service' =>
            [
                'en' => 'Salon Service',
                'ar' => 'خدمة الصالون',
                'group' => 'cart',
            ],
            'Subtotal:' =>
            [
                'en' => 'Subtotal:',
                'ar' => 'المجموع الفرعي:',
                'group' => 'booking',
            ],
            'VAT (15%):' =>
            [
                'en' => 'VAT (15%):',
                'ar' => 'ضريبة القيمة المضافة (15%):',
                'group' => 'booking',
            ],
            'Total:' =>
            [
                'en' => 'Total:',
                'ar' => 'المجموع:',
                'group' => 'booking',
            ],
            'Proceed to Checkout' =>
            [
                'en' => 'Proceed to Checkout',
                'ar' => 'المتابعة إلى الدفع',
                'group' => 'cart',
            ],
            'Confirm Action' =>
            [
                'en' => 'Confirm Action',
                'ar' => 'تأكيد الإجراء',
                'group' => 'cart',
            ],
            'Are you sure you want to proceed with this action?' =>
            [
                'en' => 'Are you sure you want to proceed with this action?',
                'ar' => 'هل أنت متأكد أنك تريد المتابعة بهذا الإجراء؟',
                'group' => 'cart',
            ],
            'Contact us - mcs.sa Salon' =>
            [
                'en' => 'Contact us - mcs.sa Salon',
                'ar' => 'اتصل بنا - صالون رين',
                'group' => 'contact',
            ],
            'Let\'s Talk Beauty!' =>
            [
                'en' => 'Let\'s Talk Beauty!',
                'ar' => 'لنتحدث عن الجمال!',
                'group' => 'contact',
            ],
            'Got Questions? Please, don\'t hesitate to get in touch with us' =>
            [
                'en' => 'Got Questions? Please, don\'t hesitate to get in touch with us',
                'ar' => 'لديك أسئلة؟ من فضلك، لا تتردد في التواصل معنا',
                'group' => 'contact',
            ],
            '8721 M Central Avenue,' =>
            [
                'en' => '8721 M Central Avenue,',
                'ar' => '8721 شارع سنترال م،',
                'group' => 'contact',
            ],
            'Los Angeles, CA 90036' =>
            [
                'en' => 'Los Angeles, CA 90036',
                'ar' => 'لوس أنجلوس، كاليفورنيا 90036',
                'group' => 'contact',
            ],
            'Mon – Wed:' =>
            [
                'en' => 'Mon – Wed:',
                'ar' => 'الإثنين - الأربعاء:',
                'group' => 'contact',
            ],
            '10:00AM - 9:00PM' =>
            [
                'en' => '10:00AM - 9:00PM',
                'ar' => '10:00 صباحًا - 9:00 مساءً',
                'group' => 'contact',
            ],
            'Thursday:' =>
            [
                'en' => 'Thursday:',
                'ar' => 'الخميس:',
                'group' => 'contact',
            ],
            '10:00AM - 7:30PM' =>
            [
                'en' => '10:00AM - 7:30PM',
                'ar' => '10:00 صباحًا - 7:30 مساءً',
                'group' => 'contact',
            ],
            'Friday:' =>
            [
                'en' => 'Friday:',
                'ar' => 'الجمعة:',
                'group' => 'contact',
            ],
            'Sun - Sun:' =>
            [
                'en' => 'Sun - Sun:',
                'ar' => 'الأحد - الأحد:',
                'group' => 'contact',
            ],
            '11:00AM - 5:00PM' =>
            [
                'en' => '11:00AM - 5:00PM',
                'ar' => '11:00 صباحًا - 5:00 مساءً',
                'group' => 'contact',
            ],
            'Send a Message' =>
            [
                'en' => 'Send a Message',
                'ar' => 'إرسال رسالة',
                'group' => 'contact',
            ],
            'Your Name*' =>
            [
                'en' => 'Your Name*',
                'ar' => 'اسمك*',
                'group' => 'contact',
            ],
            'What\'s this about?' =>
            [
                'en' => 'What\'s this about?',
                'ar' => 'ما موضوع رسالتك؟',
                'group' => 'contact',
            ],
            'Your Message ...' =>
            [
                'en' => 'Your Message ...',
                'ar' => 'رسالتك ...',
                'group' => 'contact',
            ],
            'Send Message' =>
            [
                'en' => 'Send Message',
                'ar' => 'إرسال الرسالة',
                'group' => 'contact',
            ],
            'This Week Only' =>
            [
                'en' => 'This Week Only',
                'ar' => 'هذا الأسبوع فقط',
                'group' => 'contact',
            ],
            'Get' =>
            [
                'en' => 'Get',
                'ar' => 'احصل على',
                'group' => 'contact',
            ],
            '30% OFF' =>
            [
                'en' => '30% OFF',
                'ar' => 'خصم 30٪',
                'group' => 'contact',
            ],
            'Custom Color Service' =>
            [
                'en' => 'Custom Color Service',
                'ar' => 'خدمة اللون المخصص',
                'group' => 'contact',
            ],
            'Checkout - mcs.sa Salon' =>
            [
                'en' => 'Checkout - mcs.sa Salon',
                'ar' => 'الدفع - صالون رين',
                'group' => 'checkout',
            ],
            'Personal Information' =>
            [
                'en' => 'Personal Information',
                'ar' => 'المعلومات الشخصية',
                'group' => 'checkout',
            ],
            'First Name*' =>
            [
                'en' => 'First Name*',
                'ar' => 'الاسم الأول*',
                'group' => 'checkout',
            ],
            'Last Name*' =>
            [
                'en' => 'Last Name*',
                'ar' => 'اسم العائلة*',
                'group' => 'checkout',
            ],
            'Phone Number*' =>
            [
                'en' => 'Phone Number*',
                'ar' => 'رقم الهاتف*',
                'group' => 'work-with-us',
            ],
            'Home Service Details' =>
            [
                'en' => 'Home Service Details',
                'ar' => 'تفاصيل الخدمة المنزلية',
                'group' => 'checkout',
            ],
            'Address*' =>
            [
                'en' => 'Address*',
                'ar' => 'العنوان*',
                'group' => 'checkout',
            ],
            'City*' =>
            [
                'en' => 'City*',
                'ar' => 'المدينة*',
                'group' => 'checkout',
            ],
            'Appointment Details' =>
            [
                'en' => 'Appointment Details',
                'ar' => 'تفاصيل الموعد',
                'group' => 'checkout',
            ],
            'Preferred Date*' =>
            [
                'en' => 'Preferred Date*',
                'ar' => 'التاريخ المفضل*',
                'group' => 'checkout',
            ],
            'Preferred Time*' =>
            [
                'en' => 'Preferred Time*',
                'ar' => 'الوقت المفضل*',
                'group' => 'checkout',
            ],
            'Select a time' =>
            [
                'en' => 'Select a time',
                'ar' => 'اختر وقتًا',
                'group' => 'checkout',
            ],
            '09:00 AM' =>
            [
                'en' => '09:00 AM',
                'ar' => '09:00 صباحًا',
                'group' => 'checkout',
            ],
            '10:00 AM' =>
            [
                'en' => '10:00 AM',
                'ar' => '10:00 صباحًا',
                'group' => 'checkout',
            ],
            '11:00 AM' =>
            [
                'en' => '11:00 AM',
                'ar' => '11:00 صباحًا',
                'group' => 'checkout',
            ],
            '12:00 PM' =>
            [
                'en' => '12:00 PM',
                'ar' => '12:00 ظهرًا',
                'group' => 'checkout',
            ],
            '01:00 PM' =>
            [
                'en' => '01:00 PM',
                'ar' => '01:00 مساءً',
                'group' => 'checkout',
            ],
            '02:00 PM' =>
            [
                'en' => '02:00 PM',
                'ar' => '02:00 مساءً',
                'group' => 'checkout',
            ],
            '03:00 PM' =>
            [
                'en' => '03:00 PM',
                'ar' => '03:00 مساءً',
                'group' => 'checkout',
            ],
            '04:00 PM' =>
            [
                'en' => '04:00 PM',
                'ar' => '04:00 مساءً',
                'group' => 'checkout',
            ],
            '05:00 PM' =>
            [
                'en' => '05:00 PM',
                'ar' => '05:00 مساءً',
                'group' => 'checkout',
            ],
            '06:00 PM' =>
            [
                'en' => '06:00 PM',
                'ar' => '06:00 مساءً',
                'group' => 'checkout',
            ],
            '07:00 PM' =>
            [
                'en' => '07:00 PM',
                'ar' => '07:00 مساءً',
                'group' => 'checkout',
            ],
            '08:00 PM' =>
            [
                'en' => '08:00 PM',
                'ar' => '08:00 مساءً',
                'group' => 'checkout',
            ],
            'Additional Notes' =>
            [
                'en' => 'Additional Notes',
                'ar' => 'ملاحظات إضافية',
                'group' => 'checkout',
            ],
            'Payment Method' =>
            [
                'en' => 'Payment Method',
                'ar' => 'طريقة الدفع',
                'group' => 'checkout',
            ],
            'Cash Payment' =>
            [
                'en' => 'Cash Payment',
                'ar' => 'الدفع نقدًا',
                'group' => 'checkout',
            ],
            'Card Payment' =>
            [
                'en' => 'Card Payment',
                'ar' => 'الدفع بالبطاقة',
                'group' => 'checkout',
            ],
            'Card Number*' =>
            [
                'en' => 'Card Number*',
                'ar' => 'رقم البطاقة*',
                'group' => 'checkout',
            ],
            '1234 5678 9012 3456' =>
            [
                'en' => '1234 5678 9012 3456',
                'ar' => '1234 5678 9012 3456',
                'group' => 'checkout',
            ],
            'Expiry Date*' =>
            [
                'en' => 'Expiry Date*',
                'ar' => 'تاريخ انتهاء الصلاحية*',
                'group' => 'checkout',
            ],
            'MM/YY' =>
            [
                'en' => 'MM/YY',
                'ar' => 'الشهر/السنة',
                'group' => 'checkout',
            ],
            'CVV*' =>
            [
                'en' => 'CVV*',
                'ar' => 'رمز التحقق*',
                'group' => 'checkout',
            ],
            123 =>
            [
                'en' => '123',
                'ar' => '123',
                'group' => 'checkout',
            ],
            'I accept the' =>
            [
                'en' => 'I accept the',
                'ar' => 'أوافق على',
                'group' => 'checkout',
            ],
            'Terms and Conditions' =>
            [
                'en' => 'Terms and Conditions',
                'ar' => 'الشروط والأحكام',
                'group' => 'checkout',
            ],
            'Complete Booking' =>
            [
                'en' => 'Complete Booking',
                'ar' => 'إكمال الحجز',
                'group' => 'checkout',
            ],
            'Booking Summary' =>
            [
                'en' => 'Booking Summary',
                'ar' => 'ملخص الحجز',
                'group' => 'checkout',
            ],
            'Selected Services' =>
            [
                'en' => 'Selected Services',
                'ar' => 'الخدمات المحددة',
                'group' => 'checkout',
            ],
            'No services found' =>
            [
                'en' => 'No services found',
                'ar' => 'لم يتم العثور على خدمات',
                'group' => 'checkout',
            ],
            'Back to Cart' =>
            [
                'en' => 'Back to Cart',
                'ar' => 'العودة إلى السلة',
                'group' => 'checkout',
            ],
            'About - mcs.sa Salon' =>
            [
                'en' => 'About - mcs.sa Salon',
                'ar' => 'عن - صالون mcs.sa',
                'group' => 'about',
            ],
            'About mcs.sa' =>
            [
                'en' => 'About mcs.sa',
                'ar' => 'عن mcs.sa',
                'group' => 'about',
            ],
            'Luxury salon where you will feel unique and special' =>
            [
                'en' => 'Luxury salon where you will feel unique and special',
                'ar' => 'صالون فاخر حيث ستشعر بالتميز والخصوصية',
                'group' => 'about',
            ],
            'Mind, Body and Soul' =>
            [
                'en' => 'Mind, Body and Soul',
                'ar' => 'العقل والجسد والروح',
                'group' => 'about',
            ],
            'Luxury salon where you will feel unique' =>
            [
                'en' => 'Luxury salon where you will feel unique',
                'ar' => 'صالون فاخر حيث ستشعر بالتميز',
                'group' => 'about',
            ],
            'Our History' =>
            [
                'en' => 'Our History',
                'ar' => 'تاريخنا',
                'group' => 'about',
            ],
            'We make trends accessible for everyone' =>
            [
                'en' => 'We make trends accessible for everyone',
                'ar' => 'نجعل الاتجاهات في متناول الجميع',
                'group' => 'about',
            ],
            'Feel Yourself More Beautiful' =>
            [
                'en' => 'Feel Yourself More Beautiful',
                'ar' => 'اشعر بنفسك أكثر جمالاً',
                'group' => 'about',
            ],
            'Professional Service' =>
            [
                'en' => 'Professional Service',
                'ar' => 'خدمة احترافية',
                'group' => 'about',
            ],
            'All stylists are best trained for a variety of looks' =>
            [
                'en' => 'All stylists are best trained for a variety of looks',
                'ar' => 'جميع المصممين مدربون على أفضل وجه لمجموعة متنوعة من المظاهر',
                'group' => 'about',
            ],
            'Expert Beauticians' =>
            [
                'en' => 'Expert Beauticians',
                'ar' => 'خبراء التجميل',
                'group' => 'about',
            ],
            'Experienced stylists will make your day' =>
            [
                'en' => 'Experienced stylists will make your day',
                'ar' => 'المصممون ذوو الخبرة سيجعلون يومك',
                'group' => 'about',
            ],
            'Welcome to our premium beauty sanctuary where elegance meets expertise. Our skilled beauty specialists are dedicated to delivering personalized services that enhance your natural beauty. Experience tranquility and rejuvenation in our meticulously designed space created with your comfort in mind.' =>
            [
                'en' => 'Welcome to our premium beauty sanctuary where elegance meets expertise. Our skilled beauty specialists are dedicated to delivering personalized services that enhance your natural beauty. Experience tranquility and rejuvenation in our meticulously designed space created with your comfort in mind.',
                'ar' => 'مرحبًا بك في ملاذ الجمال الفاخر لدينا حيث تلتقي الأناقة بالخبرة. يكرس أخصائيو الجمال المهرة لدينا جهودهم لتقديم خدمات مخصصة تعزز جمالك الطبيعي. استمتع بالهدوء والتجديد في مساحتنا المصممة بعناية والتي تم إنشاؤها مع وضع راحتك في الاعتبار.',
                'group' => 'about',
            ],
            'At mcs.sa Salon, we combine ancient beauty traditions with cutting-edge techniques to deliver exceptional results. Each service is tailored to your unique needs, using only premium products that nourish and protect. Our talented team continuously trains in the latest trends and methods to ensure you receive the highest quality care with every visit.' =>
            [
                'en' => 'At mcs.sa Salon, we combine ancient beauty traditions with cutting-edge techniques to deliver exceptional results. Each service is tailored to your unique needs, using only premium products that nourish and protect. Our talented team continuously trains in the latest trends and methods to ensure you receive the highest quality care with every visit.',
                'ar' => 'في صالون mcs.sa، نجمع بين تقاليد الجمال القديمة والتقنيات المتطورة لتحقيق نتائج استثنائية. يتم تصميم كل خدمة وفقًا لاحتياجاتك الفريدة، باستخدام منتجات فاخرة فقط تغذي وتحمي. يتدرب فريقنا الموهوب باستمرار على أحدث الاتجاهات والأساليب لضمان حصولك على أعلى مستوى من الرعاية في كل زيارة.',
                'group' => 'about',
            ],
            'Our salon offers a sanctuary where beauty and wellness converge. We invite you to escape the everyday and immerse yourself in luxury treatments designed to enhance your natural radiance and restore your inner balance.' =>
            [
                'en' => 'Our salon offers a sanctuary where beauty and wellness converge. We invite you to escape the everyday and immerse yourself in luxury treatments designed to enhance your natural radiance and restore your inner balance.',
                'ar' => 'يقدم صالوننا ملاذًا حيث يلتقي الجمال والعافية. ندعوك للهروب من الحياة اليومية والانغماس في العلاجات الفاخرة المصممة لتعزيز إشراقتك الطبيعية واستعادة توازنك الداخلي.',
                'group' => 'about',
            ],
            'Facials' =>
            [
                'en' => 'Facials',
                'ar' => 'علاجات الوجه',
                'group' => 'services',
            ],
            'Eyelash' =>
            [
                'en' => 'Eyelash',
                'ar' => 'رموش العين',
                'group' => 'services',
            ],
            'Eyebrow' =>
            [
                'en' => 'Eyebrow',
                'ar' => 'الحواجب',
                'group' => 'services',
            ],
            'Waxing' =>
            [
                'en' => 'Waxing',
                'ar' => 'إزالة الشعر بالشمع',
                'group' => 'services',
            ],
            'Nails' =>
            [
                'en' => 'Nails',
                'ar' => 'الأظافر',
                'group' => 'services',
            ],
            'Make-Up' =>
            [
                'en' => 'Make-Up',
                'ar' => 'المكياج',
                'group' => 'services',
            ],
            'Give the pleasure of beautiful to yourself' =>
            [
                'en' => 'Give the pleasure of beautiful to yourself',
                'ar' => 'امنح نفسك متعة الجمال',
                'group' => 'about',
            ],
            'Certified Stylists' =>
            [
                'en' => 'Certified Stylists',
                'ar' => 'مصففون معتمدون',
                'group' => 'about',
            ],
            '100% Organic Cosmetics' =>
            [
                'en' => '100% Organic Cosmetics',
                'ar' => 'مستحضرات تجميل عضوية 100٪',
                'group' => 'about',
            ],
            'Easy Online Booking' =>
            [
                'en' => 'Easy Online Booking',
                'ar' => 'حجز إلكتروني سهل',
                'group' => 'about',
            ],
            'We offer extended evening hours to accommodate your busy schedule. Our late closing times ensure you can enjoy our premium services even after work. Advanced booking is recommended to secure your preferred time slot with your favorite stylist.' =>
            [
                'en' => 'We offer extended evening hours to accommodate your busy schedule. Our late closing times ensure you can enjoy our premium services even after work. Advanced booking is recommended to secure your preferred time slot with your favorite stylist.',
                'ar' => 'نقدم ساعات مسائية ممتدة لتتناسب مع جدولك المزدحم. تضمن أوقات الإغلاق المتأخرة لدينا إمكانية الاستمتاع بخدماتنا المميزة حتى بعد العمل. يوصى بالحجز المسبق لتأمين الموعد المفضل لديك مع المصفف المفضل لديك.',
                'group' => 'about',
            ],
            'The Ultimate Relaxation for Your Mind and Body' =>
            [
                'en' => 'The Ultimate Relaxation for Your Mind and Body',
                'ar' => 'الاسترخاء المطلق لعقلك وجسمك',
                'group' => 'about',
            ],
            'Be Irresistible' =>
            [
                'en' => 'Be Irresistible',
                'ar' => 'كن لا يقاوم',
                'group' => 'about',
            ],
            'Get 30% OFF' =>
            [
                'en' => 'Get 30% OFF',
                'ar' => 'احصل على خصم 30٪',
                'group' => 'promotions',
            ],
            'Manicure + Gel Polish' =>
            [
                'en' => 'Manicure + Gel Polish',
                'ar' => 'مانيكير + طلاء جل',
                'group' => 'services',
            ],
            'Home' =>
            [
                'en' => 'Home',
                'ar' => 'الرئيسية',
                'group' => 'navigation',
            ],
            'Menu' =>
            [
                'en' => 'Menu',
                'ar' => 'القائمة',
                'group' => 'navigation',
            ],
            'Services' =>
            [
                'en' => 'Services',
                'ar' => 'الخدمات',
                'group' => 'booking',
            ],
            'More' =>
            [
                'en' => 'More',
                'ar' => 'المزيد',
                'group' => 'navigation',
            ],
            'About' =>
            [
                'en' => 'About',
                'ar' => 'من نحن',
                'group' => 'navigation',
            ],
            'FAQ' =>
            [
                'en' => 'FAQ',
                'ar' => 'الأسئلة الشائعة',
                'group' => 'navigation',
            ],
            'Contact' =>
            [
                'en' => 'Contact',
                'ar' => 'اتصل بنا',
                'group' => 'navigation',
            ],
            'Book Online' =>
            [
                'en' => 'Book Online',
                'ar' => 'احجز عبر الإنترنت',
                'group' => 'navigation',
            ],
            'mobile-logo' =>
            [
                'en' => 'mobile-logo',
                'ar' => 'شعار للجوال',
                'group' => 'general',
            ],
            'logo' =>
            [
                'en' => 'logo',
                'ar' => 'الشعار',
                'group' => 'general',
            ],
            '&copy; 2025 mcs.sa. All Rights Reserved' =>
            [
                'en' => '&copy; 2025 mcs.sa. All Rights Reserved',
                'ar' => '&copy; 2025 mcs.sa. جميع الحقوق محفوظة',
                'group' => 'footer',
            ],
            '&copy; Designed by SWU' =>
            [
                'en' => '&copy; Designed by SWU',
                'ar' => '&copy; تصميم SWU',
                'group' => 'footer',
            ],
            'Welcome to Alwan Maya Women\'s Beauty Salon' =>
            [
                'en' => 'Welcome to Alwan Maya Women\'s Beauty Salon',
                'ar' => 'مرحبا بك في صالون الوان مايا للتزيين النسائي',
                'group' => 'home',
            ],
            'A complete experience that highlights your natural beauty and boosts your confidence' =>
            [
                'en' => 'A complete experience that highlights your natural beauty and boosts your confidence',
                'ar' => 'تجربة كاملة تبرز جمالك الطبيعي وتعزز ثقتك بنفسك',
                'group' => 'home',
            ],
            'We are here for you. Book your service today' =>
            [
                'en' => 'We are here for you. Book your service today',
                'ar' => 'جمال استثناىى , تفاصيل مدروسه , وخدمات تليق بك ',
                'group' => 'home',
            ],
            'We are here for you. Book your appointment today' =>
            [
                'en' => 'We are here for you. Book your appointment today',
                'ar' => 'نحن هنا لاجلك . احجزي موعدك اليوم ',
                'group' => 'home',
            ],
            'Login' =>
            [
                'en' => 'Login',
                'ar' => 'تسجيل الدخول',
                'group' => 'navigation',
            ],
            '2025 mcs.sa. All Rights Reserved' =>
            [
                'en' => '2025 mcs.sa. All Rights Reserved',
                'ar' => '2025 mcs.sa. جميع الحقوق محفوظة',
                'group' => 'footer',
            ],
            'Designed by SWU' =>
            [
                'en' => 'Designed by SWU',
                'ar' => 'تصميم SWU',
                'group' => 'footer',
            ],
            'Our Services' =>
            [
                'en' => 'Our Services',
                'ar' => 'خدماتنا',
                'group' => 'services',
            ],
            'It\'s time to give your hair some love' =>
            [
                'en' => 'It\'s time to give your hair some love',
                'ar' => 'حان الوقت لمنح شعرك بعض الاهتمام',
                'group' => 'services',
            ],
            'Visit us during our convenient operating hours to experience our premium salon services. Our professional team is ready to welcome you and provide exceptional care for all your beauty needs. Check our schedule below to plan your visit.' =>
            [
                'en' => 'Visit us during our convenient operating hours to experience our premium salon services. Our professional team is ready to welcome you and provide exceptional care for all your beauty needs. Check our schedule below to plan your visit.',
                'ar' => 'قم بزيارتنا خلال ساعات العمل المناسبة لتجربة خدمات الصالون المميزة لدينا. فريقنا المحترف جاهز للترحيب بك وتقديم رعاية استثنائية لجميع احتياجات الجمال الخاصة بك. تحقق من جدولنا أدناه لتخطيط زيارتك.',
                'group' => 'services',
            ],
            'All Salon Services' =>
            [
                'en' => 'All Salon Services',
                'ar' => 'جميع خدمات الصالون',
                'group' => 'services',
            ],
            'Search salon services...' =>
            [
                'en' => 'Search salon services...',
                'ar' => 'البحث عن خدمات الصالون...',
                'group' => 'services',
            ],
            'Salon only' =>
            [
                'en' => 'Salon only',
                'ar' => 'في الصالون فقط',
                'group' => 'services',
            ],
            'Details' =>
            [
                'en' => 'Details',
                'ar' => 'التفاصيل',
                'group' => 'general',
            ],
            'Add to Cart' =>
            [
                'en' => 'Add to Cart',
                'ar' => 'أضف إلى السلة',
                'group' => 'cart',
            ],
            'Book a Salon Service' =>
            [
                'en' => 'Book a Salon Service',
                'ar' => 'احجز خدمة الصالون',
                'group' => 'services',
            ],
            'Select services available at our salon locations' =>
            [
                'en' => 'Select services available at our salon locations',
                'ar' => 'اختر الخدمات المتوفرة في مواقع الصالون لدينا',
                'group' => 'services',
            ],
            'Book a Home Service' =>
            [
                'en' => 'Book a Home Service',
                'ar' => 'احجز خدمة منزلية',
                'group' => 'services',
            ],
            'Select services that can be performed at your home' =>
            [
                'en' => 'Select services that can be performed at your home',
                'ar' => 'اختر الخدمات التي يمكن تنفيذها في منزلك',
                'group' => 'services',
            ],
            'All Home Services' =>
            [
                'en' => 'All Home Services',
                'ar' => 'جميع الخدمات المنزلية',
                'group' => 'services',
            ],
            'Search home services...' =>
            [
                'en' => 'Search home services...',
                'ar' => 'البحث عن الخدمات المنزلية...',
                'group' => 'services',
            ],
            'Home service' =>
            [
                'en' => 'Home service',
                'ar' => 'خدمة منزلية',
                'group' => 'cart',
            ],
            'View Our Menu' =>
            [
                'en' => 'View Our Menu',
                'ar' => 'عرض قائمتنا',
                'group' => 'services',
            ],
            'You Are Beauty' =>
            [
                'en' => 'You Are Beauty',
                'ar' => 'أنت الجمال',
                'group' => 'about',
            ],
            'Our team consists of highly trained professionals with international certifications and years of experience in the beauty industry' =>
            [
                'en' => 'Our team consists of highly trained professionals with international certifications and years of experience in the beauty industry',
                'ar' => 'يتكون فريقنا من محترفين مدربين تدريباً عالياً مع شهادات دولية وسنوات من الخبرة في صناعة الجمال',
                'group' => 'about',
            ],
            'Here is what our amazing clients are saying about us' =>
            [
                'en' => 'Here is what our amazing clients are saying about us',
                'ar' => 'إليك ما يقوله عملاؤنا الرائعون عنا',
                'group' => 'testimonials',
            ],
            'The nail technicians here are true artists! My gel manicure lasted three weeks without chipping. The salon is immaculate and maintains the highest hygiene standards. I\'ve found my permanent nail salon and wouldn\'t dream of going anywhere else.' =>
            [
                'en' => 'The nail technicians here are true artists! My gel manicure lasted three weeks without chipping. The salon is immaculate and maintains the highest hygiene standards. I\'ve found my permanent nail salon and wouldn\'t dream of going anywhere else.',
                'ar' => 'فنيو الأظافر هنا فنانون حقيقيون! استمر طلاء الأظافر الجل لمدة ثلاثة أسابيع دون تقشير. الصالون نظيف تمامًا ويحافظ على أعلى معايير النظافة. لقد وجدت صالون الأظافر الدائم الخاص بي ولا أحلم بالذهاب إلى أي مكان آخر.',
                'group' => 'testimonials',
            ],
            'Carmen M. Garcia' =>
            [
                'en' => 'Carmen M. Garcia',
                'ar' => 'كارمن م. غارسيا',
                'group' => 'testimonials',
            ],
            '9 days ago' =>
            [
                'en' => '9 days ago',
                'ar' => 'منذ 9 أيام',
                'group' => 'general',
            ],
            'The highlight service I received was absolutely phenomenal! My stylist used premium products that left my hair silky smooth and vibrant. The atmosphere was relaxing, and the attention to detail made all the difference.' =>
            [
                'en' => 'The highlight service I received was absolutely phenomenal! My stylist used premium products that left my hair silky smooth and vibrant. The atmosphere was relaxing, and the attention to detail made all the difference.',
                'ar' => 'كانت خدمة الإبراز التي تلقيتها رائعة بشكل مطلق! استخدم مصفف الشعر الخاص بي منتجات فاخرة تركت شعري ناعمًا كالحرير ومفعمًا بالحيوية. كانت الأجواء مريحة، والاهتمام بالتفاصيل أحدث كل الفرق.',
                'group' => 'testimonials',
            ],
            'Laura Merino' =>
            [
                'en' => 'Laura Merino',
                'ar' => 'لورا ميرينو',
                'group' => 'testimonials',
            ],
            '2 days ago' =>
            [
                'en' => '2 days ago',
                'ar' => 'منذ يومين',
                'group' => 'general',
            ],
            'The facial treatment at mcs.sa was transformative! The esthetician analyzed my skin concerns and customized everything. I left with glowing skin and expert advice for my home care routine. Worth every penny for the luxury experience.' =>
            [
                'en' => 'The facial treatment at mcs.sa was transformative! The esthetician analyzed my skin concerns and customized everything. I left with glowing skin and expert advice for my home care routine. Worth every penny for the luxury experience.',
                'ar' => 'كان علاج الوجه في mcs.sa تحويليًا! قام أخصائي التجميل بتحليل مخاوفي المتعلقة بالبشرة وتخصيص كل شيء. غادرت ببشرة متوهجة ونصائح خبيرة لروتين العناية المنزلية. يستحق كل قرش للتجربة الفاخرة.',
                'group' => 'testimonials',
            ],
            'Nicole Byer' =>
            [
                'en' => 'Nicole Byer',
                'ar' => 'نيكول باير',
                'group' => 'testimonials',
            ],
            '1 month ago' =>
            [
                'en' => '1 month ago',
                'ar' => 'منذ شهر',
                'group' => 'general',
            ],
            'I\'ve had eyebrow microblading done at several places, but mcs.sa Salon is exceptional. The technician took time to understand exactly what I wanted and designed a perfect shape for my face. The results look completely natural and have saved me so much time in my morning routine.' =>
            [
                'en' => 'I\'ve had eyebrow microblading done at several places, but mcs.sa Salon is exceptional. The technician took time to understand exactly what I wanted and designed a perfect shape for my face. The results look completely natural and have saved me so much time in my morning routine.',
                'ar' => 'لقد قمت بإجراء تقنية المايكروبليدنج للحواجب في عدة أماكن، ولكن صالون mcs.sa استثنائي. استغرق الفني وقتًا لفهم ما أردته بالضبط وصمم شكلاً مثاليًا لوجهي. تبدو النتائج طبيعية تمامًا ووفرت لي الكثير من الوقت في روتيني الصباحي.',
                'group' => 'testimonials',
            ],
            'Michelle Boxer' =>
            [
                'en' => 'Michelle Boxer',
                'ar' => 'ميشيل بوكسر',
                'group' => 'testimonials',
            ],
            '25 days ago' =>
            [
                'en' => '25 days ago',
                'ar' => 'منذ 25 يوم',
                'group' => 'general',
            ],
            'The bridal makeup service exceeded my expectations! The artist created a look that photographed beautifully and lasted throughout my entire wedding day and night. Everyone in my bridal party was equally thrilled with their makeovers. I\'ll treasure these memories forever.' =>
            [
                'en' => 'The bridal makeup service exceeded my expectations! The artist created a look that photographed beautifully and lasted throughout my entire wedding day and night. Everyone in my bridal party was equally thrilled with their makeovers. I\'ll treasure these memories forever.',
                'ar' => 'تجاوزت خدمة مكياج العروس توقعاتي! ابتكر الفنان مظهرًا تم تصويره بشكل جميل واستمر طوال يوم زفافي وليلته. كان الجميع في حفل زفافي متحمسين بنفس القدر لتغييراتهم. سأحتفظ بهذه الذكريات إلى الأبد.',
                'group' => 'testimonials',
            ],
            'Elizabeth Ross' =>
            [
                'en' => 'Elizabeth Ross',
                'ar' => 'إليزابيث روس',
                'group' => 'testimonials',
            ],
            '2 month ago' =>
            [
                'en' => '2 month ago',
                'ar' => 'منذ شهرين',
                'group' => 'general',
            ],
            'I\'m extremely impressed with the level of professionalism at mcs.sa Salon. Their waxing services are quick, precise, and as painless as possible. The therapist made me feel comfortable throughout the entire session, and the results are flawless.' =>
            [
                'en' => 'I\'m extremely impressed with the level of professionalism at mcs.sa Salon. Their waxing services are quick, precise, and as painless as possible. The therapist made me feel comfortable throughout the entire session, and the results are flawless.',
                'ar' => 'أنا معجب للغاية بمستوى الاحترافية في صالون mcs.sa. خدمات إزالة الشعر بالشمع لديهم سريعة ودقيقة وغير مؤلمة قدر الإمكان. جعلني المعالج أشعر بالراحة طوال الجلسة بأكملها، والنتائج لا تشوبها شائبة.',
                'group' => 'testimonials',
            ],
            'Rachel A.' =>
            [
                'en' => 'Rachel A.',
                'ar' => 'راشيل أ.',
                'group' => 'testimonials',
            ],
            'Quick Face Makeup' =>
            [
                'en' => 'Quick Face Makeup',
                'ar' => 'مكياج وجه سريع',
                'group' => 'services',
            ],
            'Salon FAQs' =>
            [
                'en' => 'Salon FAQs',
                'ar' => 'الأسئلة الشائعة للصالون',
                'group' => 'faq',
            ],
            'Everything you need to know about our salon services.' =>
            [
                'en' => 'Everything you need to know about our salon services.',
                'ar' => 'كل ما تحتاج لمعرفته عن خدمات صالوننا.',
                'group' => 'faq',
            ],
            'Have More Beauty Questions?' =>
            [
                'en' => 'Have More Beauty Questions?',
                'ar' => 'هل لديك المزيد من أسئلة الجمال؟',
                'group' => 'faq',
            ],
            'You can schedule an appointment through our website by selecting your desired service, stylist, and available time slot. Our online booking system is available 24/7 for your convenience.' =>
            [
                'en' => 'You can schedule an appointment through our website by selecting your desired service, stylist, and available time slot. Our online booking system is available 24/7 for your convenience.',
                'ar' => 'يمكنك تحديد موعد من خلال موقعنا الإلكتروني عن طريق اختيار الخدمة المطلوبة، ومصفف الشعر، والوقت المتاح. نظام الحجز عبر الإنترنت متاح على مدار الساعة طوال أيام الأسبوع لراحتك.',
                'group' => 'faq',
            ],
            'Alternatively, you can call our salon directly during business hours to speak with our receptionist who will help you book the perfect appointment time.' =>
            [
                'en' => 'Alternatively, you can call our salon directly during business hours to speak with our receptionist who will help you book the perfect appointment time.',
                'ar' => 'بدلاً من ذلك، يمكنك الاتصال بصالوننا مباشرة خلال ساعات العمل للتحدث مع موظف الاستقبال الذي سيساعدك في حجز وقت الموعد المثالي.',
                'group' => 'faq',
            ],
            'Each of our stylists specializes in different techniques and styles. We recommend viewing our stylist profiles on our website to learn about their expertise and specialties. For new clients, we offer a complimentary consultation to match you with the stylist who best suits your hair type, desired style, and personal preferences.' =>
            [
                'en' => 'Each of our stylists specializes in different techniques and styles. We recommend viewing our stylist profiles on our website to learn about their expertise and specialties. For new clients, we offer a complimentary consultation to match you with the stylist who best suits your hair type, desired style, and personal preferences.',
                'ar' => 'يتخصص كل من مصففي الشعر لدينا في تقنيات وأساليب مختلفة. نوصي بالاطلاع على ملفات مصففي الشعر على موقعنا الإلكتروني لمعرفة خبراتهم وتخصصاتهم. بالنسبة للعملاء الجدد، نقدم استشارة مجانية لمطابقتك مع مصفف الشعر الذي يناسب نوع شعرك والأسلوب المرغوب والتفضيلات الشخصية.',
                'group' => 'faq',
            ],
            'We require at least 24 hours notice for cancellations. You can cancel through our online booking system or by calling the salon directly during business hours.' =>
            [
                'en' => 'We require at least 24 hours notice for cancellations. You can cancel through our online booking system or by calling the salon directly during business hours.',
                'ar' => 'نطلب إشعارًا قبل 24 ساعة على الأقل للإلغاء. يمكنك الإلغاء من خلال نظام الحجز عبر الإنترنت أو بالاتصال بالصالون مباشرة خلال ساعات العمل.',
                'group' => 'faq',
            ],
            'Late cancellations (less than 24 hours notice) or no-shows may result in a cancellation fee of 50% of the service price on your next visit. We appreciate your understanding as this allows us to accommodate other clients.' =>
            [
                'en' => 'Late cancellations (less than 24 hours notice) or no-shows may result in a cancellation fee of 50% of the service price on your next visit. We appreciate your understanding as this allows us to accommodate other clients.',
                'ar' => 'قد يؤدي الإلغاء المتأخر (إشعار أقل من 24 ساعة) أو عدم الحضور إلى رسوم إلغاء تبلغ 50٪ من سعر الخدمة في زيارتك التالية. نقدر تفهمكم حيث يسمح هذا لنا باستيعاب عملاء آخرين.',
                'group' => 'faq',
            ],
            'Yes, we accept gift cards and certificates purchased directly from our salon. We also run seasonal promotions and special offers that can be redeemed at the time of service. All gift cards are valid for one year from the date of purchase and cannot be replaced if lost or stolen.' =>
            [
                'en' => 'Yes, we accept gift cards and certificates purchased directly from our salon. We also run seasonal promotions and special offers that can be redeemed at the time of service. All gift cards are valid for one year from the date of purchase and cannot be replaced if lost or stolen.',
                'ar' => 'نعم، نقبل بطاقات الهدايا والشهادات التي تم شراؤها مباشرة من صالوننا. كما نقدم عروضًا موسمية وعروضًا خاصة يمكن استردادها وقت تقديم الخدمة. جميع بطاقات الهدايا صالحة لمدة عام واحد من تاريخ الشراء ولا يمكن استبدالها في حالة فقدانها أو سرقتها.',
                'group' => 'faq',
            ],
            'We pride ourselves on using only premium, professional-grade hair care products in our salon.' =>
            [
                'en' => 'We pride ourselves on using only premium, professional-grade hair care products in our salon.',
                'ar' => 'نحن نفخر باستخدام منتجات العناية بالشعر الممتازة من الدرجة المهنية فقط في صالوننا.',
                'group' => 'faq',
            ],
            'Our stylists work with leading brands including Kerastase, Olaplex, Redken, and Kevin Murphy to ensure the best results for various hair types and concerns.' =>
            [
                'en' => 'Our stylists work with leading brands including Kerastase, Olaplex, Redken, and Kevin Murphy to ensure the best results for various hair types and concerns.',
                'ar' => 'يعمل مصففو الشعر لدينا مع العلامات التجارية الرائدة بما في ذلك كيراستاس، أولابلكس، ريدكن، وكيفن مورفي لضمان أفضل النتائج لمختلف أنواع الشعر والاهتمامات.',
                'group' => 'faq',
            ],
            'All products used during your service are available for purchase in our salon, and our stylists will be happy to recommend the best options for maintaining your new look at home.' =>
            [
                'en' => 'All products used during your service are available for purchase in our salon, and our stylists will be happy to recommend the best options for maintaining your new look at home.',
                'ar' => 'جميع المنتجات المستخدمة أثناء الخدمة متوفرة للشراء في صالوننا، وسيكون مصففو الشعر لدينا سعداء بتوصية أفضل الخيارات للحفاظ على مظهرك الجديد في المنزل.',
                'group' => 'faq',
            ],
            'Remember Me' =>
            [
                'en' => 'Remember Me',
                'ar' => 'تذكرني',
                'group' => 'auth',
            ],
            'Forgot Your Password?' =>
            [
                'en' => 'Forgot Your Password?',
                'ar' => 'نسيت كلمة المرور؟',
                'group' => 'auth',
            ],
            'Don\'t have an account?' =>
            [
                'en' => 'Don\'t have an account?',
                'ar' => 'ليس لديك حساب؟',
                'group' => 'auth',
            ],
            'Register Now' =>
            [
                'en' => 'Register Now',
                'ar' => 'سجل الآن',
                'group' => 'auth',
            ],
            'Email Address' =>
            [
                'en' => 'Email Address',
                'ar' => 'عنوان البريد الإلكتروني',
                'group' => 'auth',
            ],
            'Reset Password' =>
            [
                'en' => 'Reset Password',
                'ar' => 'إعادة تعيين كلمة المرور',
                'group' => 'auth',
            ],
            'Send Password Reset Link' =>
            [
                'en' => 'Send Password Reset Link',
                'ar' => 'إرسال رابط إعادة تعيين كلمة المرور',
                'group' => 'auth',
            ],
            'Back to Login' =>
            [
                'en' => 'Back to Login',
                'ar' => 'العودة إلى تسجيل الدخول',
                'group' => 'auth',
            ],
            'Register' =>
            [
                'en' => 'Register',
                'ar' => 'تسجيل',
                'group' => 'auth',
            ],
            'Confirm Password' =>
            [
                'en' => 'Confirm Password',
                'ar' => 'تأكيد كلمة المرور',
                'group' => 'auth',
            ],
            'Already have an account?' =>
            [
                'en' => 'Already have an account?',
                'ar' => 'هل لديك حساب بالفعل؟',
                'group' => 'auth',
            ],
            'Login Now' =>
            [
                'en' => 'Login Now',
                'ar' => 'تسجيل الدخول الآن',
                'group' => 'auth',
            ],
            'My Account' =>
            [
                'en' => 'My Account',
                'ar' => 'حسابي',
                'group' => 'account',
            ],
            'My Profile' =>
            [
                'en' => 'My Profile',
                'ar' => 'ملفي الشخصي',
                'group' => 'account',
            ],
            'My Bookings' =>
            [
                'en' => 'My Bookings',
                'ar' => 'حجوزاتي',
                'group' => 'account',
            ],
            'Logout' =>
            [
                'en' => 'Logout',
                'ar' => 'تسجيل الخروج',
                'group' => 'auth',
            ],
            'Dashboard' =>
            [
                'en' => 'Dashboard',
                'ar' => 'لوحة التحكم',
                'group' => 'account',
            ],
            'You have no bookings yet.' =>
            [
                'en' => 'You have no bookings yet.',
                'ar' => 'ليس لديك حجوزات حتى الآن.',
                'group' => 'account',
            ],
            'Book a Service' =>
            [
                'en' => 'Book a Service',
                'ar' => 'حجز خدمة',
                'group' => 'account',
            ],
            'Manage your account information' =>
            [
                'en' => 'Manage your account information',
                'ar' => 'إدارة معلومات حسابك',
                'group' => 'account',
            ],
            'Update Password' =>
            [
                'en' => 'Update Password',
                'ar' => 'تحديث كلمة المرور',
                'group' => 'account',
            ],
            'Current Password' =>
            [
                'en' => 'Current Password',
                'ar' => 'كلمة المرور الحالية',
                'group' => 'account',
            ],
            'New Password' =>
            [
                'en' => 'New Password',
                'ar' => 'كلمة المرور الجديدة',
                'group' => 'account',
            ],
            'Confirm New Password' =>
            [
                'en' => 'Confirm New Password',
                'ar' => 'تأكيد كلمة المرور الجديدة',
                'group' => 'account',
            ],
            'Or' =>
            [
                'en' => 'Or',
                'ar' => 'أو',
                'group' => 'general',
            ],
            'Reset Password via Email' =>
            [
                'en' => 'Reset Password via Email',
                'ar' => 'إعادة تعيين كلمة المرور عبر البريد الإلكتروني',
                'group' => 'account',
            ],
            'Profile Information' =>
            [
                'en' => 'Profile Information',
                'ar' => 'معلومات الملف الشخصي',
                'group' => 'account',
            ],
            'If you change your email, you will need to verify it again.' =>
            [
                'en' => 'If you change your email, you will need to verify it again.',
                'ar' => 'إذا قمت بتغيير بريدك الإلكتروني، فستحتاج إلى التحقق منه مرة أخرى.',
                'group' => 'account',
            ],
            'Update Profile' =>
            [
                'en' => 'Update Profile',
                'ar' => 'تحديث الملف الشخصي',
                'group' => 'account',
            ],
            'Work with us' =>
            [
                'en' => 'Work with us',
                'ar' => 'اعمل معنا',
                'group' => 'careers',
            ],
            'Work With Us - mcs.sa Salon' =>
            [
                'en' => 'Work With Us - mcs.sa Salon',
                'ar' => 'اعمل معنا - صالون mcs.sa',
                'group' => 'work-with-us',
            ],
            'Apply Now' =>
            [
                'en' => 'Apply Now',
                'ar' => 'تقدم الآن',
                'group' => 'work-with-us',
            ],
            'Join Our Team Today' =>
            [
                'en' => 'Join Our Team Today',
                'ar' => 'انضم إلى فريقنا اليوم',
                'group' => 'work-with-us',
            ],
            'Name (English)*' =>
            [
                'en' => 'Name (English)*',
                'ar' => 'الاسم (بالإنجليزية)*',
                'group' => 'work-with-us',
            ],
            'Name (Arabic)*' =>
            [
                'en' => 'Name (Arabic)*',
                'ar' => 'الاسم (بالعربية)*',
                'group' => 'work-with-us',
            ],
            'Email Address*' =>
            [
                'en' => 'Email Address*',
                'ar' => 'البريد الإلكتروني*',
                'group' => 'work-with-us',
            ],
            'Password*' =>
            [
                'en' => 'Password*',
                'ar' => 'كلمة المرور*',
                'group' => 'work-with-us',
            ],
            'Confirm Password*' =>
            [
                'en' => 'Confirm Password*',
                'ar' => 'تأكيد كلمة المرور*',
                'group' => 'work-with-us',
            ],
            'Select Position*' =>
            [
                'en' => 'Select Position*',
                'ar' => 'اختر المنصب*',
                'group' => 'work-with-us',
            ],
            'Default Start Time' =>
            [
                'en' => 'Default Start Time',
                'ar' => 'وقت البدء الافتراضي',
                'group' => 'work-with-us',
            ],
            'Default End Time' =>
            [
                'en' => 'Default End Time',
                'ar' => 'وقت الانتهاء الافتراضي',
                'group' => 'work-with-us',
            ],
            'Default Closed Day' =>
            [
                'en' => 'Default Closed Day',
                'ar' => 'يوم الإغلاق الافتراضي',
                'group' => 'work-with-us',
            ],
            'Select Closed Day' =>
            [
                'en' => 'Select Closed Day',
                'ar' => 'اختر يوم الإغلاق',
                'group' => 'work-with-us',
            ],
            'Default Home Visit Days' =>
            [
                'en' => 'Default Home Visit Days',
                'ar' => 'أيام الزيارة المنزلية الافتراضية',
                'group' => 'work-with-us',
            ],
            'Select Day' =>
            [
                'en' => 'Select Day',
                'ar' => 'اختر اليوم',
                'group' => 'work-with-us',
            ],
            'Select Product/Service' =>
            [
                'en' => 'Select Product/Service',
                'ar' => 'اختر المنتج/الخدمة',
                'group' => 'work-with-us',
            ],
            'Submit Application' =>
            [
                'en' => 'Submit Application',
                'ar' => 'إرسال الطلب',
                'group' => 'work-with-us',
            ],
            'Your application has been submitted successfully. Please check your email to verify your account. We will contact you after verification.' =>
            [
                'en' => 'Your application has been submitted successfully. Please check your email to verify your account. We will contact you after verification.',
                'ar' => 'تم تقديم طلبك بنجاح. يرجى التحقق من بريدك الإلكتروني للتحقق من حسابك. سنتواصل معك بعد التحقق.',
                'group' => 'work-with-us',
            ],
            'An error occurred while submitting your application. Please try again.' =>
            [
                'en' => 'An error occurred while submitting your application. Please try again.',
                'ar' => 'حدث خطأ أثناء تقديم طلبك. يرجى المحاولة مرة أخرى.',
                'group' => 'work-with-us',
            ],
            'Booking Confirmed - mcs.sa Salon' =>
            [
                'en' => 'Booking Confirmed - mcs.sa Salon',
                'ar' => 'تأكيد الحجز - صالون mcs.sa',
                'group' => 'booking',
            ],
            'Your Booking Has Been Received' =>
            [
                'en' => 'Your Booking Has Been Received',
                'ar' => 'تم استلام حجزك',
                'group' => 'booking',
            ],
            'Your booking is pending payment. Complete your payment online to confirm your appointment.' =>
            [
                'en' => 'Your booking is pending payment. Complete your payment online to confirm your appointment.',
                'ar' => 'حجزك في انتظار الدفع. أكمل الدفع عبر الإنترنت لتأكيد موعدك.',
                'group' => 'booking',
            ],
            'Your Booking Has Been Confirmed' =>
            [
                'en' => 'Your Booking Has Been Confirmed',
                'ar' => 'تم تأكيد حجزك',
                'group' => 'booking',
            ],
            'We\'ve confirmed your booking request. A confirmation email with the details of your appointment has been sent to your email address.' =>
            [
                'en' => 'We\'ve confirmed your booking request. A confirmation email with the details of your appointment has been sent to your email address.',
                'ar' => 'لقد أكدنا طلب حجزك. تم إرسال بريد إلكتروني للتأكيد مع تفاصيل موعدك إلى عنوان بريدك الإلكتروني.',
                'group' => 'booking',
            ],
            'Your Service Has Been Completed' =>
            [
                'en' => 'Your Service Has Been Completed',
                'ar' => 'تم إكمال خدمتك',
                'group' => 'booking',
            ],
            'Thank you for choosing our services. We hope you enjoyed your experience with us.' =>
            [
                'en' => 'Thank you for choosing our services. We hope you enjoyed your experience with us.',
                'ar' => 'شكرًا لاختيار خدماتنا. نأمل أن تكون قد استمتعت بتجربتك معنا.',
                'group' => 'booking',
            ],
            'Your Booking Has Been Cancelled' =>
            [
                'en' => 'Your Booking Has Been Cancelled',
                'ar' => 'تم إلغاء حجزك',
                'group' => 'booking',
            ],
            'Your booking has been cancelled. If you have any questions regarding the cancellation, please contact us.' =>
            [
                'en' => 'Your booking has been cancelled. If you have any questions regarding the cancellation, please contact us.',
                'ar' => 'تم إلغاء حجزك. إذا كان لديك أي أسئلة بخصوص الإلغاء، يرجى الاتصال بنا.',
                'group' => 'booking',
            ],
            'Customer Information' =>
            [
                'en' => 'Customer Information',
                'ar' => 'معلومات العميل',
                'group' => 'booking',
            ],
            'Name:' =>
            [
                'en' => 'Name:',
                'ar' => 'الاسم:',
                'group' => 'booking',
            ],
            'Email:' =>
            [
                'en' => 'Email:',
                'ar' => 'البريد الإلكتروني:',
                'group' => 'booking',
            ],
            'Phone:' =>
            [
                'en' => 'Phone:',
                'ar' => 'الهاتف:',
                'group' => 'booking',
            ],
            'Additional Notes:' =>
            [
                'en' => 'Additional Notes:',
                'ar' => 'ملاحظات إضافية:',
                'group' => 'booking',
            ],
            'Address:' =>
            [
                'en' => 'Address:',
                'ar' => 'العنوان:',
                'group' => 'booking',
            ],
            'Staff:' =>
            [
                'en' => 'Staff:',
                'ar' => 'الموظف:',
                'group' => 'booking',
            ],
            'Service Type:' =>
            [
                'en' => 'Service Type:',
                'ar' => 'نوع الخدمة:',
                'group' => 'booking',
            ],
            'Date:' =>
            [
                'en' => 'Date:',
                'ar' => 'التاريخ:',
                'group' => 'booking',
            ],
            'SAR' =>
            [
                'en' => 'SAR',
                'ar' => 'ريال',
                'group' => 'booking',
            ],
            'Discount:' =>
            [
                'en' => 'Discount:',
                'ar' => 'الخصم:',
                'group' => 'booking',
            ],
            'Other Taxes:' =>
            [
                'en' => 'Other Taxes:',
                'ar' => 'ضرائب أخرى:',
                'group' => 'booking',
            ],
            'Payment Method:' =>
            [
                'en' => 'Payment Method:',
                'ar' => 'طريقة الدفع:',
                'group' => 'booking',
            ],
            'Payment Status:' =>
            [
                'en' => 'Payment Status:',
                'ar' => 'حالة الدفع:',
                'group' => 'booking',
            ],
            'Awaiting Payment' =>
            [
                'en' => 'Awaiting Payment',
                'ar' => 'في انتظار الدفع',
                'group' => 'booking',
            ],
            'Paid (Cash):' =>
            [
                'en' => 'Paid (Cash):',
                'ar' => 'المدفوع (نقداً):',
                'group' => 'booking',
            ],
            'Paid (Online):' =>
            [
                'en' => 'Paid (Online):',
                'ar' => 'المدفوع (عبر الإنترنت):',
                'group' => 'booking',
            ],
            'Amount Due:' =>
            [
                'en' => 'Amount Due:',
                'ar' => 'المبلغ المستحق:',
                'group' => 'booking',
            ],
            'Cancel Booking' =>
            [
                'en' => 'Cancel Booking',
                'ar' => 'إلغاء الحجز',
                'group' => 'booking',
            ],
            'Reschedule Appointment' =>
            [
                'en' => 'Reschedule Appointment',
                'ar' => 'إعادة جدولة الموعد',
                'group' => 'booking',
            ],
            'Pay Now' =>
            [
                'en' => 'Pay Now',
                'ar' => 'ادفع الآن',
                'group' => 'booking',
            ],
            'Select Payment Method' =>
            [
                'en' => 'Select Payment Method',
                'ar' => 'اختر طريقة الدفع',
                'group' => 'booking',
            ],
            'Visa/Mastercard' =>
            [
                'en' => 'Visa/Mastercard',
                'ar' => 'فيزا/ماستركارد',
                'group' => 'booking',
            ],
            'Mada' =>
            [
                'en' => 'Mada',
                'ar' => 'مدى',
                'group' => 'booking',
            ],
            'What\'s Next?' =>
            [
                'en' => 'What\'s Next?',
                'ar' => 'ما هي الخطوة التالية؟',
                'group' => 'booking',
            ],
            'Our team will review your booking and contact you to confirm your appointment. Please make sure your contact information is accurate.' =>
            [
                'en' => 'Our team will review your booking and contact you to confirm your appointment. Please make sure your contact information is accurate.',
                'ar' => 'سيقوم فريقنا بمراجعة حجزك والاتصال بك لتأكيد موعدك. يرجى التأكد من دقة معلومات الاتصال الخاصة بك.',
                'group' => 'booking',
            ],
            'Your appointment is confirmed! For salon services, please arrive 10 minutes before your scheduled time. For home services, our staff will contact you to confirm the location details - please stay reachable.' =>
            [
                'en' => 'Your appointment is confirmed! For salon services, please arrive 10 minutes before your scheduled time. For home services, our staff will contact you to confirm the location details - please stay reachable.',
                'ar' => 'تم تأكيد موعدك! لخدمات الصالون، يرجى الوصول قبل 10 دقائق من وقتك المحدد. لخدمات المنزل، سيتصل بك موظفونا لتأكيد تفاصيل الموقع - يرجى البقاء متاحًا.',
                'group' => 'booking',
            ],
            'Your appointment is confirmed! Our staff will contact you to confirm the location details for your home service. Please make sure to stay reachable on your registered contact number.' =>
            [
                'en' => 'Your appointment is confirmed! Our staff will contact you to confirm the location details for your home service. Please make sure to stay reachable on your registered contact number.',
                'ar' => 'تم تأكيد موعدك! سيتصل بك موظفونا لتأكيد تفاصيل الموقع لخدمة المنزل. يرجى التأكد من البقاء متاحًا على رقم الاتصال المسجل لديك.',
                'group' => 'booking',
            ],
            'Your appointment is confirmed! Please arrive 10 minutes before your scheduled time. If you need to prepare anything specific for your service, our team will contact you.' =>
            [
                'en' => 'Your appointment is confirmed! Please arrive 10 minutes before your scheduled time. If you need to prepare anything specific for your service, our team will contact you.',
                'ar' => 'تم تأكيد موعدك! يرجى الوصول قبل 10 دقائق من وقتك المحدد. إذا كنت بحاجة إلى تحضير أي شيء محدد لخدمتك، سيتصل بك فريقنا.',
                'group' => 'booking',
            ],
            'We hope you enjoyed your service! Your feedback is important to us. Consider leaving a review of your experience to help us improve.' =>
            [
                'en' => 'We hope you enjoyed your service! Your feedback is important to us. Consider leaving a review of your experience to help us improve.',
                'ar' => 'نأمل أن تكون قد استمتعت بخدمتنا! ملاحظاتك مهمة بالنسبة لنا. فكر في ترك تقييم لتجربتك لمساعدتنا على التحسين.',
                'group' => 'booking',
            ],
            'Your booking has been cancelled. If you\'d like to reschedule, please feel free to make a new booking at your convenience.' =>
            [
                'en' => 'Your booking has been cancelled. If you\'d like to reschedule, please feel free to make a new booking at your convenience.',
                'ar' => 'تم إلغاء حجزك. إذا كنت ترغب في إعادة الجدولة، فلا تتردد في إجراء حجز جديد في الوقت المناسب لك.',
                'group' => 'booking',
            ],
            'If you need to modify or cancel your booking, please contact us at' =>
            [
                'en' => 'If you need to modify or cancel your booking, please contact us at',
                'ar' => 'إذا كنت بحاجة إلى تعديل أو إلغاء حجزك، يرجى الاتصال بنا على',
                'group' => 'booking',
            ],
            'or' =>
            [
                'en' => 'or',
                'ar' => 'أو',
                'group' => 'booking',
            ],
            'Print Invoice' =>
            [
                'en' => 'Print Invoice',
                'ar' => 'طباعة الفاتورة',
                'group' => 'booking',
            ],
            'View All Bookings' =>
            [
                'en' => 'View All Bookings',
                'ar' => 'عرض جميع الحجوزات',
                'group' => 'booking',
            ],
            'Are you sure you want to cancel this booking?' =>
            [
                'en' => 'Are you sure you want to cancel this booking?',
                'ar' => 'هل أنت متأكد أنك تريد إلغاء هذا الحجز؟',
                'group' => 'booking',
            ],
            'Optional Note (Reason for cancellation)' =>
            [
                'en' => 'Optional Note (Reason for cancellation)',
                'ar' => 'ملاحظة اختيارية (سبب الإلغاء)',
                'group' => 'booking',
            ],
            'Enter reason for cancellation (optional)' =>
            [
                'en' => 'Enter reason for cancellation (optional)',
                'ar' => 'أدخل سبب الإلغاء (اختياري)',
                'group' => 'booking',
            ],
            'Close' =>
            [
                'en' => 'Close',
                'ar' => 'إغلاق',
                'group' => 'booking',
            ],
            'Confirm Cancellation' =>
            [
                'en' => 'Confirm Cancellation',
                'ar' => 'تأكيد الإلغاء',
                'group' => 'booking',
            ],
            'Processing...' =>
            [
                'en' => 'Processing...',
                'ar' => 'جاري المعالجة...',
                'group' => 'booking',
            ],
            'Payment initialization failed: ' =>
            [
                'en' => 'Payment initialization failed: ',
                'ar' => 'فشل تهيئة الدفع: ',
                'group' => 'booking',
            ],
            'Unknown error' =>
            [
                'en' => 'Unknown error',
                'ar' => 'خطأ غير معروف',
                'group' => 'booking',
            ],
            'Payment initialization failed. Please try again.' =>
            [
                'en' => 'Payment initialization failed. Please try again.',
                'ar' => 'فشل تهيئة الدفع. يرجى المحاولة مرة أخرى.',
                'group' => 'booking',
            ],
            'Pay Online' =>
            [
                'en' => 'Pay Online',
                'ar' => 'ادفع عبر الإنترنت',
                'group' => 'booking',
            ],
            'Cancelling...' =>
            [
                'en' => 'Cancelling...',
                'ar' => 'جاري الإلغاء...',
                'group' => 'booking',
            ],
            'Booking cancelled successfully' =>
            [
                'en' => 'Booking cancelled successfully',
                'ar' => 'تم إلغاء الحجز بنجاح',
                'group' => 'booking',
            ],
            'Failed to cancel booking' =>
            [
                'en' => 'Failed to cancel booking',
                'ar' => 'فشل إلغاء الحجز',
                'group' => 'booking',
            ],
            'Service not found' =>
            [
                'en' => 'Service not found',
                'ar' => 'الخدمة غير موجودة',
                'group' => 'booking',
            ],
            'No booking data found. Please add services to your cart first.' =>
            [
                'en' => 'No booking data found. Please add services to your cart first.',
                'ar' => 'لم يتم العثور على بيانات الحجز. يرجى إضافة الخدمات إلى سلة التسوق أولاً.',
                'group' => 'booking',
            ],
            'Invalid booking data. Please try again.' =>
            [
                'en' => 'Invalid booking data. Please try again.',
                'ar' => 'بيانات الحجز غير صالحة. يرجى المحاولة مرة أخرى.',
                'group' => 'booking',
            ],
            'Reservation not found.' =>
            [
                'en' => 'Reservation not found.',
                'ar' => 'لم يتم العثور على الحجز.',
                'group' => 'booking',
            ],
            'This reservation does not belong to your account.' =>
            [
                'en' => 'This reservation does not belong to your account.',
                'ar' => 'هذا الحجز لا ينتمي إلى حسابك.',
                'group' => 'booking',
            ],
            'One or more of your selected service times have already been booked and paid for. Please reschedule your appointment.' =>
            [
                'en' => 'One or more of your selected service times have already been booked and paid for. Please reschedule your appointment.',
                'ar' => 'تم حجز ودفع واحد أو أكثر من أوقات الخدمة التي اخترتها. يرجى إعادة جدولة موعدك.',
                'group' => 'booking',
            ],
            'No services selected for booking.' =>
            [
                'en' => 'No services selected for booking.',
                'ar' => 'لم يتم اختيار خدمات للحجز.',
                'group' => 'booking',
            ],
            'Error calculating order totals' =>
            [
                'en' => 'Error calculating order totals',
                'ar' => 'خطأ في حساب إجماليات الطلب',
                'group' => 'booking',
            ],
            'No point of sale found in the system.' =>
            [
                'en' => 'No point of sale found in the system.',
                'ar' => 'لم يتم العثور على نقطة بيع في النظام.',
                'group' => 'booking',
            ],
            'Booking created successfully!' =>
            [
                'en' => 'Booking created successfully!',
                'ar' => 'تم إنشاء الحجز بنجاح!',
                'group' => 'booking',
            ],
            'Validation failed' =>
            [
                'en' => 'Validation failed',
                'ar' => 'فشل التحقق',
                'group' => 'booking',
            ],
            'An error occurred while processing your booking: ' =>
            [
                'en' => 'An error occurred while processing your booking: ',
                'ar' => 'حدث خطأ أثناء معالجة حجزك: ',
                'group' => 'booking',
            ],
            'This reservation is already cancelled' =>
            [
                'en' => 'This reservation is already cancelled',
                'ar' => 'تم إلغاء هذا الحجز بالفعل',
                'group' => 'booking',
            ],
            'Profile Status: Pending Approval' =>
            [
                'en' => 'Profile Status: Pending Approval',
                'ar' => 'حالة الملف الشخصي: في انتظار الموافقة',
                'group' => 'staff_profile',
            ],
            'Your staff profile is currently awaiting administrative review. While your profile is being reviewed, you can continue to update your information and complete your profile details. Once approved, your profile will become visible to users.' =>
            [
                'en' => 'Your staff profile is currently awaiting administrative review. While your profile is being reviewed, you can continue to update your information and complete your profile details. Once approved, your profile will become visible to users.',
                'ar' => 'ملفك الشخصي كموظف في انتظار المراجعة الإدارية حاليًا. أثناء مراجعة ملفك الشخصي، يمكنك الاستمرار في تحديث معلوماتك وإكمال تفاصيل ملفك الشخصي. بمجرد الموافقة، سيصبح ملفك الشخصي مرئيًا للمستخدمين.',
                'group' => 'staff_profile',
            ],
            'profile' =>
            [
                'en' => 'profile',
                'ar' => 'الملف الشخصي',
                'group' => 'staff_profile',
            ],
            'Profile' =>
            [
                'en' => 'Profile',
                'ar' => 'الملف الشخصي',
                'group' => 'staff_profile',
            ],
            'Bookings' =>
            [
                'en' => 'Bookings',
                'ar' => 'الحجوزات',
                'group' => 'staff_profile',
            ],
            'Position (Arabic)' =>
            [
                'en' => 'Position (Arabic)',
                'ar' => 'المنصب (بالعربية)',
                'group' => 'staff_profile',
            ],
            'Position (English)' =>
            [
                'en' => 'Position (English)',
                'ar' => 'المنصب (بالإنجليزية)',
                'group' => 'staff_profile',
            ],
            'Images' =>
            [
                'en' => 'Images',
                'ar' => 'الصور',
                'group' => 'staff_profile',
            ],
            'Resume' =>
            [
                'en' => 'Resume',
                'ar' => 'السيرة الذاتية',
                'group' => 'staff_profile',
            ],
            'Services & Products' =>
            [
                'en' => 'Services & Products',
                'ar' => 'الخدمات والمنتجات',
                'group' => 'staff_profile',
            ],
            'Account Information' =>
            [
                'en' => 'Account Information',
                'ar' => 'معلومات الحساب',
                'group' => 'staff_profile',
            ],
            'Default Working Hours' =>
            [
                'en' => 'Default Working Hours',
                'ar' => 'ساعات العمل الافتراضية',
                'group' => 'staff_profile',
            ],
            'Default Day Off' =>
            [
                'en' => 'Default Day Off',
                'ar' => 'يوم العطلة الافتراضي',
                'group' => 'staff_profile',
            ],
            'These default values will not effect your current week schedule, it will only be used for automatically generated schedules.' =>
            [
                'en' => 'These default values will not effect your current week schedule, it will only be used for automatically generated schedules.',
                'ar' => 'لن تؤثر هذه القيم الافتراضية على جدول الأسبوع الحالي، وسيتم استخدامها فقط للجداول المُنشأة تلقائيًا.',
                'group' => 'staff_profile',
            ],
            'Select days' =>
            [
                'en' => 'Select days',
                'ar' => 'اختر الأيام',
                'group' => 'staff_profile',
            ],
            'Terms and Conditions - mcs.sa Salon' =>
            [
                'en' => 'Terms and Conditions - mcs.sa Salon',
                'ar' => 'الشروط والأحكام - صالون mcs.sa',
                'group' => 'terms',
            ],
            'Please read our privacy policy carefully' =>
            [
                'en' => 'Please read our privacy policy carefully',
                'ar' => 'يرجى قراءة سياسة الخصوصية الخاصة بنا بعناية',
                'group' => 'terms',
            ],
            'Privacy Policy – Maya Colors Ladies Beauty Salon' =>
            [
                'en' => 'Privacy Policy – Maya Colors Ladies Beauty Salon',
                'ar' => 'سياسة الخصوصية – صالون الوان مايا للتزيين النسائي',
                'group' => 'terms',
            ],
            'At Maya Colors Salon, we value your privacy greatly. This policy explains how we collect, use, and protect your personal information when you use our website.' =>
            [
                'en' => 'At Maya Colors Salon, we value your privacy greatly. This policy explains how we collect, use, and protect your personal information when you use our website.',
                'ar' => 'في صالون الوان مايا ، نُولي خصوصيتك اهتماماً بالغاً. توضح هذه السياسة كيفية جمعنا، واستخدامنا، وحمايتنا لمعلوماتك الشخصية عند استخدامك لموقعنا.',
                'group' => 'terms',
            ],
            'Information We Collect' =>
            [
                'en' => 'Information We Collect',
                'ar' => 'المعلومات التي نقوم بجمعها',
                'group' => 'terms',
            ],
            'Name, phone number, and email address.' =>
            [
                'en' => 'Name, phone number, and email address.',
                'ar' => 'الاسم، ورقم الهاتف، وعنوان البريد الإلكتروني.',
                'group' => 'terms',
            ],
            'Booking and appointment details.' =>
            [
                'en' => 'Booking and appointment details.',
                'ar' => 'تفاصيل الحجز والمواعيد.',
                'group' => 'terms',
            ],
            'Any information you provide when contacting us through forms or email.' =>
            [
                'en' => 'Any information you provide when contacting us through forms or email.',
                'ar' => 'أي معلومات تقدمها عند التواصل معنا عبر النماذج أو البريد الإلكتروني.',
                'group' => 'terms',
            ],
            'How We Use Information' =>
            [
                'en' => 'How We Use Information',
                'ar' => 'كيفية استخدام المعلومات',
                'group' => 'terms',
            ],
            'To confirm and manage appointments.' =>
            [
                'en' => 'To confirm and manage appointments.',
                'ar' => 'لتأكيد الحجز وإدارة المواعيد.',
                'group' => 'terms',
            ],
            'To improve our services and your user experience.' =>
            [
                'en' => 'To improve our services and your user experience.',
                'ar' => 'لتحسين خدماتنا وتجربتك كمستخدم.',
                'group' => 'terms',
            ],
            'To respond to your inquiries and requests.' =>
            [
                'en' => 'To respond to your inquiries and requests.',
                'ar' => 'للرد على استفساراتك وطلبك.',
                'group' => 'terms',
            ],
            'To send promotional offers (with your prior consent).' =>
            [
                'en' => 'To send promotional offers (with your prior consent).',
                'ar' => 'لإرسال عروض ترويجية (عند موافقتك المسبقة).',
                'group' => 'terms',
            ],
            'Information Sharing' =>
            [
                'en' => 'Information Sharing',
                'ar' => 'مشاركة المعلومات',
                'group' => 'terms',
            ],
            'We do not share your personal information with any third party, unless required legally or to improve service (such as electronic booking systems).' =>
            [
                'en' => 'We do not share your personal information with any third party, unless required legally or to improve service (such as electronic booking systems).',
                'ar' => 'لا نشارك معلوماتك الشخصية مع أي طرف ثالث، إلا إذا تطلب الأمر قانونياً أو لتحسين الخدمة (مثل أنظمة الحجز الإلكترونية).',
                'group' => 'terms',
            ],
            'Information Protection' =>
            [
                'en' => 'Information Protection',
                'ar' => 'حماية المعلومات',
                'group' => 'terms',
            ],
            'We use technical and organizational security measures to protect your data from unauthorized access or use.' =>
            [
                'en' => 'We use technical and organizational security measures to protect your data from unauthorized access or use.',
                'ar' => 'نستخدم تدابير أمان تقنية وتنظيمية لحماية بياناتك من الوصول أو الاستخدام غير المصرح به.',
                'group' => 'terms',
            ],
            'Cookies' =>
            [
                'en' => 'Cookies',
                'ar' => 'ملفات تعريف الارتباط',
                'group' => 'terms',
            ],
            'The website may use cookies to improve your experience, and you can control browser settings to disable them.' =>
            [
                'en' => 'The website may use cookies to improve your experience, and you can control browser settings to disable them.',
                'ar' => 'قد يستخدم الموقع ملفات تعريف الارتباط لتحسين تجربتك، ويمكنك التحكم في إعدادات المتصفح لتعطيلها.',
                'group' => 'terms',
            ],
            'Your Rights' =>
            [
                'en' => 'Your Rights',
                'ar' => 'حقوقك',
                'group' => 'terms',
            ],
            'You can request to modify or delete your personal data at any time by contacting us.' =>
            [
                'en' => 'You can request to modify or delete your personal data at any time by contacting us.',
                'ar' => 'يمكنك طلب تعديل أو حذف بياناتك الشخصية في أي وقت عبر التواصل معنا.',
                'group' => 'terms',
            ],
            'Amendments' =>
            [
                'en' => 'Amendments',
                'ar' => 'التعديلات',
                'group' => 'terms',
            ],
            'We may update this privacy policy from time to time, and changes will be posted on this page.' =>
            [
                'en' => 'We may update this privacy policy from time to time, and changes will be posted on this page.',
                'ar' => 'قد نقوم بتحديث سياسة الخصوصية هذه من وقت لآخر، وسيتم نشر التعديلات على هذه الصفحة.',
                'group' => 'terms',
            ],
            'Contact Us' =>
            [
                'en' => 'Contact Us',
                'ar' => 'للتواصل معنا',
                'group' => 'terms',
            ],
            'If you have any questions or inquiries about the privacy policy, please contact us via' =>
            [
                'en' => 'If you have any questions or inquiries about the privacy policy, please contact us via',
                'ar' => 'إذا كانت لديك أي أسئلة أو استفسارات حول سياسة الخصوصية، يُرجى التواصل معنا عبر',
                'group' => 'terms',
            ],
            '[Email Address]' =>
            [
                'en' => '[Email Address]',
                'ar' => '[البريد الإلكتروني]',
                'group' => 'terms',
            ],
            '[Phone Number]' =>
            [
                'en' => '[Phone Number]',
                'ar' => '[رقم الهاتف]',
                'group' => 'terms',
            ],
            '[Address]' =>
            [
                'en' => '[Address]',
                'ar' => '[العنوان]',
                'group' => 'terms',
            ],
            'You must accept the Terms and Conditions to continue.' =>
            [
                'en' => 'You must accept the Terms and Conditions to continue.',
                'ar' => 'يجب عليك قبول الشروط والأحكام للمتابعة.',
                'group' => 'terms',
            ],
            'Select the services and products this staff member can provide' =>
            [
                'en' => 'Select the services and products this staff member can provide',
                'ar' => 'اختر الخدمات والمنتجات التي يمكن لهذا الموظف تقديمها',
                'group' => 'staff_profile',
            ],
            'Schedule' =>
            [
                'en' => 'Schedule',
                'ar' => 'جدولة',
                'group' => 'cart',
            ],
            'Reschedule' =>
            [
                'en' => 'Reschedule',
                'ar' => 'إعادة جدولة',
                'group' => 'cart',
            ],
            'Scheduled' =>
            [
                'en' => 'Scheduled',
                'ar' => 'تم الجدولة',
                'group' => 'cart',
            ],
            'Any available' =>
            [
                'en' => 'Any available',
                'ar' => 'أي متاح',
                'group' => 'cart',
            ],
            'minutes' =>
            [
                'en' => 'minutes',
                'ar' => 'دقائق',
                'group' => 'cart',
            ],
            'Salon service' =>
            [
                'en' => 'Salon service',
                'ar' => 'خدمة صالون',
                'group' => 'cart',
            ],
            // Adding translations from StaffController
            'Validation error' =>
            [
                'en' => 'Validation error',
                'ar' => 'خطأ في التحقق',
                'group' => 'staff',
            ],
            'Failed to send verification email to: ' =>
            [
                'en' => 'Failed to send verification email to: ',
                'ar' => 'فشل في إرسال بريد التحقق إلى: ',
                'group' => 'staff',
            ],
            'Attempting to send notification email to point of sale user: ' =>
            [
                'en' => 'Attempting to send notification email to point of sale user: ',
                'ar' => 'محاولة إرسال بريد إلكتروني للإشعار إلى مستخدم نقطة البيع: ',
                'group' => 'staff',
            ],
            'Failed to send notification email to: ' =>
            [
                'en' => 'Failed to send notification email to: ',
                'ar' => 'فشل في إرسال بريد إلكتروني للإشعار إلى: ',
                'group' => 'staff',
            ],
            'Could not send notification email: Point of sale or user not found' =>
            [
                'en' => 'Could not send notification email: Point of sale or user not found',
                'ar' => 'تعذر إرسال بريد إلكتروني للإشعار: نقطة البيع أو المستخدم غير موجود',
                'group' => 'staff',
            ],
            'Staff application submitted successfully. Please check your email to verify your account.' =>
            [
                'en' => 'Staff application submitted successfully. Please check your email to verify your account.',
                'ar' => 'تم تقديم طلب الموظف بنجاح. يرجى التحقق من بريدك الإلكتروني للتحقق من حسابك.',
                'group' => 'staff',
            ],
            'Staff application error: ' =>
            [
                'en' => 'Staff application error: ',
                'ar' => 'خطأ في طلب الموظف: ',
                'group' => 'staff',
            ],
            'Failed to submit staff application' =>
            [
                'en' => 'Failed to submit staff application',
                'ar' => 'فشل في تقديم طلب الموظف',
                'group' => 'staff',
            ],
            'English name is required' =>
            [
                'en' => 'English name is required',
                'ar' => 'الاسم باللغة الإنجليزية مطلوب',
                'group' => 'auth',
            ],
            'English name must be text' =>
            [
                'en' => 'English name must be text',
                'ar' => 'يجب أن يكون الاسم باللغة الإنجليزية نصًا',
                'group' => 'staff',
            ],
            'English name cannot exceed 255 characters' =>
            [
                'en' => 'English name cannot exceed 255 characters',
                'ar' => 'لا يمكن أن يتجاوز الاسم باللغة الإنجليزية 255 حرفًا',
                'group' => 'staff',
            ],
            'Arabic name is required' =>
            [
                'en' => 'Arabic name is required',
                'ar' => 'الاسم باللغة العربية مطلوب',
                'group' => 'auth',
            ],
            'Arabic name must be text' =>
            [
                'en' => 'Arabic name must be text',
                'ar' => 'يجب أن يكون الاسم باللغة العربية نصًا',
                'group' => 'staff',
            ],
            'Arabic name cannot exceed 255 characters' =>
            [
                'en' => 'Arabic name cannot exceed 255 characters',
                'ar' => 'لا يمكن أن يتجاوز الاسم باللغة العربية 255 حرفًا',
                'group' => 'staff',
            ],
            'Please enter a valid email address' =>
            [
                'en' => 'Please enter a valid email address',
                'ar' => 'يرجى إدخال عنوان بريد إلكتروني صالح',
                'group' => 'auth',
            ],
            'This email is already registered' =>
            [
                'en' => 'This email is already registered',
                'ar' => 'هذا البريد الإلكتروني مسجل بالفعل',
                'group' => 'auth',
            ],
            'Phone number is required' =>
            [
                'en' => 'Phone number is required',
                'ar' => 'رقم الهاتف مطلوب',
                'group' => 'auth',
            ],
            'Phone number must be text' =>
            [
                'en' => 'Phone number must be text',
                'ar' => 'يجب أن يكون رقم الهاتف نصًا',
                'group' => 'staff',
            ],
            'Phone number cannot exceed 20 characters' =>
            [
                'en' => 'Phone number cannot exceed 20 characters',
                'ar' => 'لا يمكن أن يتجاوز رقم الهاتف 20 حرفًا',
                'group' => 'auth',
            ],
            'Password is required' =>
            [
                'en' => 'Password is required',
                'ar' => 'كلمة المرور مطلوبة',
                'group' => 'auth',
            ],
            'Password must be text' =>
            [
                'en' => 'Password must be text',
                'ar' => 'يجب أن تكون كلمة المرور نصًا',
                'group' => 'staff',
            ],
            'Password must be at least 8 characters' =>
            [
                'en' => 'Password must be at least 8 characters',
                'ar' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل',
                'group' => 'auth',
            ],
            'Password confirmation does not match' =>
            [
                'en' => 'Password confirmation does not match',
                'ar' => 'تأكيد كلمة المرور غير متطابق',
                'group' => 'auth',
            ],
            'Position is required' =>
            [
                'en' => 'Position is required',
                'ar' => 'المنصب مطلوب',
                'group' => 'staff',
            ],
            'Position must be text' =>
            [
                'en' => 'Position must be text',
                'ar' => 'يجب أن يكون المنصب نصًا',
                'group' => 'staff',
            ],
            'Address must be text' =>
            [
                'en' => 'Address must be text',
                'ar' => 'يجب أن يكون العنوان نصًا',
                'group' => 'staff',
            ],
            'Address cannot exceed 255 characters' =>
            [
                'en' => 'Address cannot exceed 255 characters',
                'ar' => 'لا يمكن أن يتجاوز العنوان 255 حرفًا',
                'group' => 'auth',
            ],
            'You must accept the terms and conditions' =>
            [
                'en' => 'You must accept the terms and conditions',
                'ar' => 'يجب عليك قبول الشروط والأحكام',
                'group' => 'staff',
            ],
            'Resume must be a file' =>
            [
                'en' => 'Resume must be a file',
                'ar' => 'يجب أن تكون السيرة الذاتية ملفًا',
                'group' => 'staff',
            ],
            'Resume must be a PDF, DOC, or DOCX file' =>
            [
                'en' => 'Resume must be a PDF, DOC, or DOCX file',
                'ar' => 'يجب أن تكون السيرة الذاتية ملف PDF أو DOC أو DOCX',
                'group' => 'staff',
            ],
            'Resume cannot exceed 10MB' =>
            [
                'en' => 'Resume cannot exceed 10MB',
                'ar' => 'لا يمكن أن تتجاوز السيرة الذاتية 10 ميغابايت',
                'group' => 'staff',
            ],
            'Images must be an array' =>
            [
                'en' => 'Images must be an array',
                'ar' => 'يجب أن تكون الصور مصفوفة',
                'group' => 'staff',
            ],
            'Files must be images' =>
            [
                'en' => 'Files must be images',
                'ar' => 'يجب أن تكون الملفات صورًا',
                'group' => 'staff',
            ],
            'Images must be JPEG, PNG, JPG, or GIF format' =>
            [
                'en' => 'Images must be JPEG, PNG, JPG, or GIF format',
                'ar' => 'يجب أن تكون الصور بتنسيق JPEG أو PNG أو JPG أو GIF',
                'group' => 'staff',
            ],
            'Images cannot exceed 5MB each' =>
            [
                'en' => 'Images cannot exceed 5MB each',
                'ar' => 'لا يمكن أن تتجاوز كل صورة 5 ميغابايت',
                'group' => 'staff',
            ],
            'Start time must be in H:i format' =>
            [
                'en' => 'Start time must be in H:i format',
                'ar' => 'يجب أن يكون وقت البدء بتنسيق H:i',
                'group' => 'staff',
            ],
            'End time must be in H:i format' =>
            [
                'en' => 'End time must be in H:i format',
                'ar' => 'يجب أن يكون وقت الانتهاء بتنسيق H:i',
                'group' => 'staff',
            ],
            'Closed day must be text' =>
            [
                'en' => 'Closed day must be text',
                'ar' => 'يجب أن يكون يوم الإغلاق نصًا',
                'group' => 'staff',
            ],
            'Home visit days must be an array' =>
            [
                'en' => 'Home visit days must be an array',
                'ar' => 'يجب أن تكون أيام الزيارة المنزلية مصفوفة',
                'group' => 'staff',
            ],
            'Home visit days must be text' =>
            [
                'en' => 'Home visit days must be text',
                'ar' => 'يجب أن تكون أيام الزيارة المنزلية نصًا',
                'group' => 'staff',
            ],
            'Home visit days must be valid days of the week' =>
            [
                'en' => 'Home visit days must be valid days of the week',
                'ar' => 'يجب أن تكون أيام الزيارة المنزلية أيامًا صالحة من الأسبوع',
                'group' => 'staff',
            ],
            'Products and services must be an array' =>
            [
                'en' => 'Products and services must be an array',
                'ar' => 'يجب أن تكون المنتجات والخدمات مصفوفة',
                'group' => 'staff',
            ],
            'Selected products and services do not exist' =>
            [
                'en' => 'Selected products and services do not exist',
                'ar' => 'المنتجات والخدمات المحددة غير موجودة',
                'group' => 'staff',
            ],
            'View More' =>
            [
                'en' => 'View More',
                'ar' => 'عرض المزيد',
                'group' => 'home',
            ],
            'Make Up' =>
            [
                'en' => 'Make Up',
                'ar' => 'المكياج',
                'group' => 'services',
            ],
            'Look stunning for any occasion with professional makeup that enhances your natural beauty and boosts your confidence.' =>
            [
                'en' => 'Look stunning for any occasion with professional makeup that enhances your natural beauty and boosts your confidence.',
                'ar' => 'تألقي في أي مناسبة مع مكياج احترافي يبرز جمالك الطبيعي ويعزز ثقتك بنفسك.',
                'group' => 'services',
            ],
            'Pamper your hands and feet with our manicures and pedicures, featuring stylish finishes and top-quality care.' =>
            [
                'en' => 'Pamper your hands and feet with our manicures and pedicures, featuring stylish finishes and top-quality care.',
                'ar' => 'دللي يديك وقدميك مع خدمات المانيكير والباديكير لدينا، المتميزة بلمسات أنيقة وعناية عالية الجودة.',
                'group' => 'services',
            ],
            'Smooth, flawless skin with our gentle yet effective waxing services—ideal for long-lasting results.' =>
            [
                'en' => 'Smooth, flawless skin with our gentle yet effective waxing services—ideal for long-lasting results.',
                'ar' => 'بشرة ناعمة وخالية من العيوب مع خدمات إزالة الشعر بالشمع اللطيفة والفعالة - مثالية للحصول على نتائج طويلة الأمد.',
                'group' => 'services',
            ],
            'Revitalize your skin with our rejuvenating facials, tailored to your skin type for a healthy, radiant glow.' =>
            [
                'en' => 'Revitalize your skin with our rejuvenating facials, tailored to your skin type for a healthy, radiant glow.',
                'ar' => 'جددي بشرتك مع جلسات تنظيف الوجه المنعشة، المصممة خصيصًا لنوع بشرتك للحصول على إشراقة صحية ومتألقة.',
                'group' => 'services',
            ],
            'Home Services Menu' =>
            [
                'en' => 'Home Services Menu',
                'ar' => 'قائمة خدمات المنزل',
                'group' => 'menu',
            ],
            'Salon Services Menu' =>
            [
                'en' => 'Salon Services Menu',
                'ar' => 'قائمة خدمات الصالون',
                'group' => 'menu',
            ],
            'Book Now' =>
            [
                'en' => 'Book Now',
                'ar' => 'احجز الآن',
                'group' => 'menu',
            ],
            'Best Selling Services' =>
            [
                'en' => 'Best Selling Services',
                'ar' => 'أفضل الخدمات المبيعة',
                'group' => 'menu',
            ],
            'Trending Services' =>
            [
                'en' => 'Trending Services',
                'ar' => 'الخدمات المتطرفة',
                'group' => 'menu',
            ],
            'All Categories' =>
            [
                'en' => 'All Categories',
                'ar' => 'كل التصنيفات',
                'group' => 'menu',
            ],
            'Service Location' =>
            [
                'en' => 'Service Location',
                'ar' => 'موقع الخدمة',
                'group' => 'menu',
            ],
            'Select Location' =>
            [
                'en' => 'Select Location',
                'ar' => 'اختر الموقع',
                'group' => 'menu',
            ],
            'Salon' =>
            [
                'en' => 'Salon',
                'ar' => 'الصالون',
                'group' => 'menu',
            ],
            'Home service only' =>
            [
                'en' => 'Home service only',
                'ar' => 'خدمة منزلية فقط',
                'group' => 'menu',
            ],
            'Salon service only' =>
            [
                'en' => 'Salon service only',
                'ar' => 'خدمة صالون فقط',
                'group' => 'menu',
            ],
            'Duration' =>
            [
                'en' => 'Duration',
                'ar' => 'المدة',
                'group' => 'menu',
            ],
            'Description' =>
            [
                'en' => 'Description',
                'ar' => 'الوصف',
                'group' => 'menu',
            ],
            'You May Also Like' =>
            [
                'en' => 'You May Also Like',
                'ar' => 'قد تحب أيضًا',
                'group' => 'menu',
            ],
            'View Details' =>
            [
                'en' => 'View Details',
                'ar' => 'عرض التفاصيل',
                'group' => 'menu',
            ],
            'Are you sure you want to remove all items from your cart?' =>
            [
                'en' => 'Are you sure you want to remove all items from your cart?',
                'ar' => 'هل أنت متأكد أنك تريد إزالة جميع العناصر من سلة التسوق؟',
                'group' => 'cart',
            ],
            'Clear Cart' =>
            [
                'en' => 'Clear Cart',
                'ar' => 'مسح السلة',
                'group' => 'cart',
            ],
            'Please schedule the following services before proceeding to checkout:' =>
            [
                'en' => 'Please schedule the following services before proceeding to checkout:',
                'ar' => 'يرجى ترتيب الخدمات التالية قبل الانتقال إلى الدفع:',
                'group' => 'cart',
            ],
            'Item removed from cart' =>
            [
                'en' => 'Item removed from cart',
                'ar' => 'تم إزالة العنصر من السلة',
                'group' => 'cart',
            ],
            'Cart has been cleared' =>
            [
                'en' => 'Cart has been cleared',
                'ar' => 'تم مسح السلة',
                'group' => 'cart',
            ],
            'Your cart is empty. Please add services before proceeding to checkout.' =>
            [
                'en' => 'Your cart is empty. Please add services before proceeding to checkout.',
                'ar' => 'سلتك فارغة. يرجى إضافة خدمات قبل المتابعة إلى الدفع.',
                'group' => 'cart',
            ],
            'Please schedule the following services before proceeding to checkout:' =>
            [
                'en' => 'Please schedule the following services before proceeding to checkout:',
                'ar' => 'يرجى جدولة الخدمات التالية قبل المتابعة إلى الدفع:',
                'group' => 'cart',
            ],
            'Service has been unscheduled. Please schedule it again.' =>
            [
                'en' => 'Service has been unscheduled. Please schedule it again.',
                'ar' => 'تم إلغاء جدولة الخدمة. يرجى جدولتها مرة أخرى.',
                'group' => 'cart',
            ],
            'Please select a time slot first' =>
            [
                'en' => 'Please select a time slot first',
                'ar' => 'يرجى تحديد فترة زمنية أولاً',
                'group' => 'cart',
            ],
            'Service scheduled successfully' =>
            [
                'en' => 'Service scheduled successfully',
                'ar' => 'تم جدولة الخدمة بنجاح',
                'group' => 'cart',
            ],
            'Could not find the service in the cart' =>
            [
                'en' => 'Could not find the service in the cart',
                'ar' => 'تعذر العثور على الخدمة في السلة',
                'group' => 'cart',
            ],
            'Please select a staff member first to view available time slots' =>
            [
                'en' => 'Please select a staff member first to view available time slots',
                'ar' => 'يرجى تحديد أحد أعضاء الفريق أولاً لعرض الفترات الزمنية المتاحة',
                'group' => 'cart',
            ],
            'Are you sure you want to remove all items from your cart?' =>
            [
                'en' => 'Are you sure you want to remove all items from your cart?',
                'ar' => 'هل أنت متأكد أنك تريد إزالة جميع العناصر من سلتك؟',
                'group' => 'cart',
            ],
            'Schedule Service' =>
            [
                'en' => 'Schedule Service',
                'ar' => 'جدول الخدمة',
                'group' => 'cart',
            ],
            'Select Date' =>
            [
                'en' => 'Select Date',
                'ar' => 'اختر التاريخ',
                'group' => 'cart',
            ],
            'Select Staff' =>
            [
                'en' => 'Select Staff',
                'ar' => 'اختر الموظف',
                'group' => 'cart',
            ],
            'Select Time' =>
            [
                'en' => 'Select Time',
                'ar' => 'اختر الوقت',
                'group' => 'cart',
            ],
            'days available' =>
            [
                'en' => 'days available',
                'ar' => 'أيام متاحة',
                'group' => 'cart',
            ],
            'No available dates found' =>
            [
                'en' => 'No available dates found',
                'ar' => 'لا يوجد تواريخ متاحة',
                'group' => 'cart',
            ],
            'Error loading available dates' =>
            [
                'en' => 'Error loading available dates',
                'ar' => 'خطأ في تحميل التواريخ المتاحة',
                'group' => 'cart',
            ],
            'Choose a staff member' =>
            [
                'en' => 'Choose a staff member',
                'ar' => 'اختر أحد أعضاء الفريق',
                'group' => 'cart',
            ],
            'No staff available for the selected date' =>
            [
                'en' => 'No staff available for the selected date',
                'ar' => 'لا يوجد موظفين متاحين للتاريخ المختار',
                'group' => 'cart',
            ],
            'Error loading available staff' =>
            [
                'en' => 'Error loading available staff',
                'ar' => 'خطأ في تحميل الموظفين المتاحين',
                'group' => 'cart',
            ],
            'Working hours' =>
            [
                'en' => 'Working hours',
                'ar' => 'ساعات العمل',
                'group' => 'cart',
            ],
            'Select a date' =>
            [
                'en' => 'Select a date',
                'ar' => 'اختر تاريخ',
                'group' => 'cart',
            ],
            'Select a time' =>
            [
                'en' => 'Select a time',
                'ar' => 'اختر الوقت',
                'group' => 'cart',
            ],
            'This service is already scheduled. If you want to change the quantity first un schedule it' =>
            [
                'en' => 'This service is already scheduled. If you want to change the quantity first un schedule it',
                'ar' => 'تم جدولة هذه الخدمة. إذا أردت تغيير الكمية أولاً، يرجى إلغاء الجدولة.',
                'group' => 'cart',
            ],
            'Error loading staff schedule' =>
            [
                'en' => 'Error loading staff schedule',
                'ar' => 'خطأ في تحميل جدول الموظفين',
                'group' => 'cart',
            ],
            'No staff available for the selected date and time' =>
            [
                'en' => 'No staff available for the selected date and time',
                'ar' => 'لا يوجد موظفين متاحين للتاريخ والوقت المختار',
                'group' => 'cart',
            ],
            'Select a time slot' =>
            [
                'en' => 'Select a time slot',
                'ar' => 'اختر فترة زمنية',
                'group' => 'cart',
            ],
            'Select a staff member' =>
            [
                'en' => 'Select a staff member',
                'ar' => 'اختر أحد أعضاء الفريق',
                'group' => 'cart',
            ],
            'Please Login or Register' =>
            [
                'en' => 'Please Login or Register',
                'ar' => 'يرجى تسجيل الدخول أو إنشاء حساب',
                'group' => 'cart',
            ],
            'To complete your booking, please login or create an account.' =>
            [
                'en' => 'To complete your booking, please login or create an account.',
                'ar' => 'لإكمال حجزك، يرجى تسجيل الدخول أو إنشاء حساب',
                'group' => 'cart',
            ],
            'Apply' =>
            [
                'en' => 'Apply',
                'ar' => 'تطبيق',
                'group' => 'cart',
            ],
            'Remove' =>
            [
                'en' => 'Remove',
                'ar' => 'إزالة',
                'group' => 'cart',
            ],
            'Discount applied successfully' =>
            [
                'en' => 'Discount applied successfully',
                'ar' => 'تم تطبيق الخصم بنجاح',
                'group' => 'cart',
            ],
            'Enter discount code' =>
            [
                'en' => 'Enter discount code',
                'ar' => 'أدخل رمز الخصم',
                'group' => 'cart',
            ],
            'Login now' =>
            [
                'en' => 'Login now',
                'ar' => 'تسجيل الدخول الآن',
                'group' => 'cart',
            ],
            'Designed by' =>
            [
                'en' => 'Designed by',
                'ar' => 'تصميم بواسطة',
                'group' => 'footer',
            ],
            'Full Name' =>
            [
                'en' => 'Full Name',
                'ar' => 'الاسم الكامل',
                'group' => 'checkout',
            ],
            'Not scheduled' =>
            [
                'en' => 'Not scheduled',
                'ar' => 'لم يتم جدولة',
                'group' => 'checkout',
            ],
            'Select Location on Map' =>
            [
                'en' => 'Select Location on Map',
                'ar' => 'اختر موقع الخريطة',
                'group' => 'checkout',
            ],
            'Search for your location' =>
            [
                'en' => 'Search for your location',
                'ar' => 'ابحث عن موقعك',
                'group' => 'checkout',
            ],
            'Drag the marker to adjust your exact location' =>
            [
                'en' => 'Drag the marker to adjust your exact location',
                'ar' => 'قم بسحب العلامة لتعديل موقعك بالضبط',
                'group' => 'checkout',
            ],
            'Get My Location' =>
            [
                'en' => 'Get My Location',
                'ar' => 'احصل على موقعي',
                'group' => 'checkout',
            ],
            'Proceed to Payment' =>
            [
                'en' => 'Proceed to Payment',
                'ar' => 'اتمام الدفع',
                'group' => 'checkout',
            ],
            'You saved' =>
            [
                'en' => 'You saved',
                'ar' => 'لقد حفظت',
                'group' => 'checkout',
            ],
            'Complete Your Payment' =>
            [
                'en' => 'Complete Your Payment',
                'ar' => 'اتمام الدفع',
                'group' => 'checkout',
            ],
            'Please enter your payment details to complete the booking.' =>
            [
                'en' => 'Please enter your payment details to complete the booking.',
                'ar' => 'يرجى إدخال تفاصيل الدفع لإكمال الحجز.',
                'group' => 'checkout',
            ],
            'Cancel and return' =>
            [
                'en' => 'Cancel and return',
                'ar' => 'إلغاء وإرجاع',
                'group' => 'checkout',
            ],
            'Phone number should contain only digits and valid formatting characters' =>
            [
                'en' => 'Phone number should contain only digits and valid formatting characters',
                'ar' => 'يجب أن يحتوي رقم الهاتف على أرقام فقط وأحرف تنسيق صالحة',
                'group' => 'checkout',
            ],
            'Please enter a valid phone number' =>
            [
                'en' => 'Please enter a valid phone number',
                'ar' => 'يرجى إدخال رقم هاتف صالح',
                'group' => 'checkout',
            ],

            'Your profile has been updated successfully.' =>
            [
                'en' => 'Your profile has been updated successfully.',
                'ar' => 'تم تحديث الملف الشخصي بنجاح',
                'group' => 'profile',
            ],
            'Profile updated' =>
            [
                'en' => 'Profile updated',
                'ar' => 'تم تحديث الملف الشخصي',
                'group' => 'profile',
            ],

            'Failed to update profile' =>
            [
                'en' => 'Failed to update profile',
                'ar' => 'فشل تحديث الملف الشخصي',
                'group' => 'profile',
            ],
            'Could not update your profile: ' =>
            [
                'en' => 'Could not update your profile: ',
                'ar' => 'لم يتم تحديث الملف الشخصي: ',
                'group' => 'profile',
            ],
            'Home Visits' =>
            [
                'en' => 'Home Visits',
                'ar' => 'الزيارات المنزلية',
                'group' => 'bookings',
            ],
            'Can Visit Home' =>
            [
                'en' => 'Can Visit Home',
                'ar' => 'يمكن زيارة المنزل',
                'group' => 'bookings',
            ],
            'Indicates if the staff member can visit client homes during this time slot' =>
            [
                'en' => 'Indicates if the staff member can visit client homes during this time slot',
                'ar' => 'يشير إلى ما إذا كان الموظف يمكن زيارة منازل العملاء خلال هذه الفترة الزمنية',
                'group' => 'bookings',
            ],
            'Current Week Only' =>
            [
                'en' => 'Current Week Only',
                'ar' => 'الأسبوع الحالي فقط',
                'group' => 'bookings',
            ],
            'Home Visit Availability' =>
            [
                'en' => 'Home Visit Availability',
                'ar' => 'الفترات المتاحة للزيارات المنزلية',
                'group' => 'bookings',
            ],
            'Leave this field empty if this is a product and not a service' =>
            [
                'en' => 'Leave this field empty if this is a product and not a service',
                'ar' => 'اترك هذه المنطقة فارغة لو كان هذا منتج وليس خدمة',
                'group' => 'bookings',
            ],
            'Select applicable taxes for this product' =>
            [
                'en' => 'Select applicable taxes for this product',
                'ar' => 'اختر الضرائب المناسبة لهذا المنتج',
                'group' => 'bookings',
            ],
            'Taxes' =>
            [
                'en' => 'Taxes',
                'ar' => 'الضرائب',
                'group' => 'bookings',
            ],
            'Sale price at Salon (with taxes)' =>
            [
                'en' => 'Sale price at Salon (with taxes)',
                'ar' => 'سعر البيع في المحل (مع الضرائب)',
                'group' => 'bookings',
            ],
            'Point of Sale\'s Time Intervals' =>
            [
                'en' => 'Point of Sale\'s Time Intervals',
                'ar' => 'فترات الوقت للمحلات',
                'group' => 'bookings',
            ],
            'Time Interval Already Exists' =>
            [
                'en' => 'Time Interval Already Exists',
                'ar' => 'فترة زمنية موجودة بالفعل',
                'group' => 'notifications',
            ],
            'A time interval for this date already exists.' =>
            [
                'en' => 'A time interval for this date already exists.',
                'ar' => 'فترة زمنية لهذا التاريخ موجودة بالفعل.',
                'group' => 'notifications',
            ],
            'Click here to edit' =>
            [
                'en' => 'Click here to edit',
                'ar' => 'انقر هنا للتعديل',
                'group' => 'actions',
            ],
            'Update all existing future dates with same day' =>
            [
                'en' => 'Update all existing future dates with same day',
                'ar' => 'تحديث جميع التواريخ المستقبلية الموجودة بنفس اليوم',
                'group' => 'reservations',
            ],
            'Update existing :day settings' =>
            [
                'en' => 'Update existing :day settings',
                'ar' => 'تحديث إعدادات :day الموجودة',
                'group' => 'reservations',
            ],
            'Settings Applied' =>
            [
                'en' => 'Settings Applied',
                'ar' => 'تم تطبيق الإعدادات',
                'group' => 'notifications',
            ],
            'The settings have been applied to all future :dayName dates.' =>
            [
                'en' => 'The settings have been applied to all future :dayName dates.',
                'ar' => 'تم تطبيق الإعدادات على جميع تواريخ :dayName المستقبلية.',
                'group' => 'notifications',
            ],
            'Updated :count existing settings.' =>
            [
                'en' => 'Updated :count existing settings.',
                'ar' => 'تم تحديث :count من الإعدادات الموجودة.',
                'group' => 'notifications',
            ],
            'No existing settings found to update.' =>
            [
                'en' => 'No existing settings found to update.',
                'ar' => 'لم يتم العثور على إعدادات موجودة للتحديث.',
                'group' => 'notifications',
            ],
            'Time Intervals Updated' =>
            [
                'en' => 'Time Intervals Updated',
                'ar' => 'تم تحديث الفترات الزمنية',
                'group' => 'notifications',
            ],
            'Updating all future :day time intervals' =>
            [
                'en' => 'Updating all future :day time intervals',
                'ar' => 'تحديث جميع الفترات الزمنية المستقبلية ليوم :day',
                'group' => 'notifications',
            ],
            'Updated :count time intervals:' =>
            [
                'en' => 'Updated :count time intervals:',
                'ar' => 'تم تحديث :count من الفترات الزمنية:',
                'group' => 'notifications',
            ],
            'No future time intervals found for :day.' =>
            [
                'en' => 'No future time intervals found for :day.',
                'ar' => 'لم يتم العثور على فترات زمنية مستقبلية ليوم :day.',
                'group' => 'notifications',
            ],
            'Make sure you\'ve created future time intervals first.' =>
            [
                'en' => 'Make sure you\'ve created future time intervals first.',
                'ar' => 'تأكد من إنشاء فترات زمنية مستقبلية أولاً.',
                'group' => 'notifications',
            ],
            'Settings Applied' =>
            [
                'en' => 'Settings Applied',
                'ar' => 'تم تطبيق الإعدادات',
                'group' => 'notifications',
            ],
            'Updating all future :day reservation settings' =>
            [
                'en' => 'Updating all future :day reservation settings',
                'ar' => 'تحديث جميع إعدادات الحجز المستقبلية ليوم :day',
                'group' => 'notifications',
            ],
            'Updated :count settings:' =>
            [
                'en' => 'Updated :count settings:',
                'ar' => 'تم تحديث :count من الإعدادات:',
                'group' => 'notifications',
            ],
            'No future settings found for :day.' =>
            [
                'en' => 'No future settings found for :day.',
                'ar' => 'لم يتم العثور على إعدادات مستقبلية ليوم :day.',
                'group' => 'notifications',
            ],
            'Make sure you\'ve created future reservation settings first.' =>
            [
                'en' => 'Make sure you\'ve created future reservation settings first.',
                'ar' => 'تأكد من إنشاء إعدادات حجز مستقبلية أولاً.',
                'group' => 'notifications',
            ],
            'Total of Paid Amount' =>
            [
                'en' => 'Total of Paid Amount',
                'ar' => 'إجمالي المبلغ المدفوع',
                'group' => 'notifications',
            ],
            'Total of Amount' =>
            [
                'en' => 'Total of Amount',
                'ar' => 'إجمالي المبلغ',
                'group' => 'notifications',
            ],
            'Total of Discount' =>
            [
                'en' => 'Total of Discount',
                'ar' => 'إجمالي الخصم',
                'group' => 'notifications',
            ],
            'Total of Other Discount' =>
            [
                'en' => 'Total of Other Discount',
                'ar' => 'إجمالي الخصم الأخرى',
                'group' => 'notifications',
            ],
            'Total of VAT' =>
            [
                'en' => 'Total of VAT',
                'ar' => 'إجمالي الضريبة',
                'group' => 'notifications',
            ],
            'Total of Subtotal' =>
            [
                'en' => 'Total of Subtotal',
                'ar' => 'إجمالي المبلغ الفرعي',
                'group' => 'notifications',
            ],
            'Invoice Number' =>
            [
                'en' => 'Invoice Number',
                'ar' => 'رقم الفاتورة',
                'group' => 'notifications',
            ],
            'VAT Amount' =>
            [
                'en' => 'VAT Amount',
                'ar' => 'إجمالي الضريبة',
                'group' => 'notifications',
            ],
            'Discount Amount' =>
            [
                'en' => 'Discount Amount',
                'ar' => 'إجمالي الخصم',
                'group' => 'notifications',
            ],
            'Other Discount' =>
            [
                'en' => 'Other Discount',
                'ar' => 'إجمالي الخصم الأخرى',
                'group' => 'notifications',
            ],
            'Summary' =>
            [
                'en' => 'Summary',
                'ar' => 'ملخص',
                'group' => 'notifications',
            ],
            'Export' =>
            [
                'en' => 'Export',
                'ar' => 'تصدير',
                'group' => 'actions',
            ],
            'Total of Discount Amount' =>
            [
                'en' => 'Total of Discount Amount',
                'ar' => 'إجمالي الخصم',
                'group' => 'notifications',
            ],
            'Total of VAT Amount' =>
            [
                'en' => 'Total of VAT Amount',
                'ar' => 'إجمالي الضريبة',
                'group' => 'notifications',
            ],
            'Total of Price' =>
            [
                'en' => 'Total of Price',
                'ar' => 'إجمالي السعر',
                'group' => 'notifications',
            ],
            'VAT #' =>
            [
                'en' => 'VAT #',
                'ar' => 'رقم الضريبة',
                'group' => 'notifications',
            ],
            'Qty' =>
            [
                'en' => 'Qty',
                'ar' => 'الكمية',
                'group' => 'notifications',
            ],
            'home' =>
            [
                'en' => 'home',
                'ar' => 'المنزل',
                'group' => 'notifications',
            ],
            'salon' =>
            [
                'en' => 'salon',
                'ar' => 'المحل',
                'group' => 'notifications',
            ],





        ];

        // Create or update translations for both languages
        foreach ($translations as $key => $values) {
            // English - Case Sensitive
            $englishTranslation = Translation::where('language_id', $englishLanguage->id)
                ->whereRaw('BINARY `key_name` = ?', [$key])
                ->first();

            if ($englishTranslation) {
                // Update existing record
                $englishTranslation->update([
                    'group' => $values['group'],
                    'translation' => $values['en'],
                ]);
            } else {
                // Create new record
                Translation::create([
                    'language_id' => $englishLanguage->id,
                    'key_name' => $key,
                    'group' => $values['group'],
                    'translation' => $values['en'],
                ]);
            }

            // Arabic - Case Sensitive
            $arabicTranslation = Translation::where('language_id', $arabicLanguage->id)
                ->whereRaw('BINARY `key_name` = ?', [$key])
                ->first();

            if ($arabicTranslation) {
                // Update existing record
                $arabicTranslation->update([
                    'group' => $values['group'],
                    'translation' => $values['ar'],
                ]);
            } else {
                // Create new record
                Translation::create([
                    'language_id' => $arabicLanguage->id,
                    'key_name' => $key,
                    'group' => $values['group'],
                    'translation' => $values['ar'],
                ]);
            }
        }

        $this->command->info('Translations created successfully for English and Arabic languages.');
    }
}
