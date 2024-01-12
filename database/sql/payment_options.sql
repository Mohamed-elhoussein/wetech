INSERT INTO `payment_options` (`id`, `label`, `label_en`, `sub_text`, `value`, `type`, `payment_type`, `created_at`, `updated_at`, `payment_gateway`) VALUES
(1, 'الدفع عند الاستلام', 'Cash on delivery', '(يتم اضافة 19 ريال)', 19, 'plus', 'online', NULL, NULL, 'epay'),
(2, 'دفع 20 ريال من اجمالي الفاتورة', 'Pay 20 riyals of the total bill', '(غير مستردة في حال الغاء العميل الطلب)', 20, 'sub', 'online', NULL, NULL, 'epay'),
(3, 'دفع عبر البطاقة', 'Pay via card', '(بدون رسوم اضافية )', 0, 'default', 'online', NULL, NULL, 'epay');
