# Product_Planning
Product planning using Raspberry Pi and PHP

Bir yazıcının üretiminin ve stok durumunun izlendiği bir sistemdir. 4 farklı istasyon vardır ve her istasyonda Raspberry pi bulunmaktadır. Raspberry pi'dan alınan veriler php ile web sayfasında gösterilmektedir.

## Php dosyasını çalıştırmak için gerekli işlemler

Öncelikle LAMP Serveri kurunuz. Sonrasında kodlarda bulunan server ve phpmyadmin bağlantısı için gerekli olan yerlere kendi bilgilerinizi yazınız.

Veritabanı için oluşturduğum 2 adet tablo bulunmaktadır. Mysql kullandım ve birinci tablom olan Personel tablosunda id ve isim satırları vardır. İkinci tablom olan Printer tablosunda ise id,isim,ürün(farklı ürünler olabilir),tarih,durum ve istasyon numarası bulunmaktadır.
Sonrasında www klasörünün içine tracker klasörünü yükleyebilirsiniz. 

## Python dosyasını çalıştırmak için gerekli işlemler
`python main.py` komutunu yazarak main dosyasını çalıştırabilirsiniz.
