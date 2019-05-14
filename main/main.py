#! /usr/bin/env python
# -*- coding: utf-8 -*-

from Tkinter import *
import tkMessageBox
#Json verisi göndermek için aşağıdaki kütüphaneler import edildi. 
import urllib
import requests
import json

#Dropdown variable Printer Name
PRINTERNAME = [
"Printer1",
"Printer2"
] #etc

#Dropdown variable Printer Count
COUNT = [
1,
2,
3,
4
] #etc

#Dropdown variable Personel Name
PERSONEL = [
(1,"station1"),
(2,"station2"),
(3,"station3"),
(4,"station4"),
(5,"station5")
] #etc

url = 'http://localhost/tracker/connection.php'	#connection IP 
var_personel = ''	#Personel Seçimi için tanımlanan global değişken.

#-------TİMER GLOBAL VARİABLE--------
state = False
# Our time structure [min, sec, centsec]
timer = [0, 0, 0]
# The format is padding all the 
pattern = '{0:02d}:{1:02d}:{2:02d}'

#--------MAİN CLASS------------------
class ProductionPlanning(Tk):
	def __init__(self, *args, **kwargs):
		Tk.__init__(self, *args, **kwargs)
		#Screen Size
		self.minsize(320,240)
		self.maxsize(320,240)
		self.geometry("320x240")

		#Fullscrenn
		Tk.attributes(self,"-fullscreen", True)
		container = Frame(self)

		container.pack(side ="top",fill="both",expand = True)
		container.grid_rowconfigure(0,weight=1)
		container.grid_columnconfigure(0,weight=1)
		self.frames= {}
		
		#Other Pages
		for Pages in (LoginPage , MasterPage , EkstraPage , PausePage) :
			frame = Pages(container, self)
			
			self.frames[Pages] = frame
	
			frame.grid(row =0, column =0 ,sticky="nsew")

		self.show_frame(LoginPage)	#Start Frame
	
	#Ekranda Gösterilecek Frame
	def show_frame(self,cont):
		frame = self.frames[cont]
		frame.tkraise()

#--------PERSONEL LOGIN CLASS-----------
class LoginPage(Frame):
	
	def __init__(self,parent,controller):
		Frame.__init__(self, parent)
		
		def change_dropdown(*args):	#Personel Dropdown değiştirildiğinde global değişkene seçilen personel ataması yapar.
			global var_personel
			var_personel = personel_variable.get()
			
		def send():
			if var_personel == '' : 
				message = tkMessageBox.showerror("Hata","Personel Seçimi Yapınız.")
			#Seçilen Personel Ekrana Yazdırıldı.  			
			personel = var_personel
 			print personel[1]
    			print personel[5:-2] 

			result = tkMessageBox.askquestion("Printer","Personel Seçim İşlemini Onaylıyormusunuz ?"  ,icon='warning')
	   		if result == 'yes':
				tkMessageBox.showinfo("Printer"," Seçim Yapıldı. :)")
				controller.show_frame(MasterPage)
			else:
				tkMessageBox.showinfo("Printer","İşlem İptal Edildi.")

		#--------------Background Photo---------------------
		login_photo=PhotoImage(file="background.png")
		background_label = Label(self, image=login_photo)
		background_label.place(x=0, y=0, relwidth=1, relheight=1)
		background_label.image=login_photo
		
		#--------------Send Button--------------------------
		send_button = Button(self, text="GÖNDER", command = send , height=2, width=10, compound=LEFT, bg='white' , font = ('Sans','12','bold') )
		send_button.place(x = 90, y = 100)
			
		#-------------Personel Dropdown Menu----------------
		personel_variable = StringVar(self)
		personel_variable.set("Üretim Personelini Seçiniz") # default value
		personel_menu = OptionMenu(self, personel_variable, *PERSONEL)
		personel_menu["height"] = 2
		personel_menu["width"] = 24
		personel_menu["bg"] = 'white'
		personel_menu["font"] = ('Sans','12','bold')
		personel_menu.place(x = 10, y = 10)
		menu = personel_menu.nametowidget(personel_menu.menuname) 
		menu.configure(font=('Sans', 20, 'bold'), bg='white') 
		# link function to change dropdown
		personel_variable.trace('w',change_dropdown)

#---------------MASTERPAGE CLASS-------------
class MasterPage(Frame):
	def __init__(self,parent,controller):
		Frame.__init__(self, parent)
		
		#-----TİMER----------
		def update_timeText():
		    global timer
		    global state
		    if (state):
		        # Every time this function is called, 
		        # we will increment 1 centisecond (1/100 of a second)
		        timer[2] += 1
		        
		        # Every 100 centisecond is equal to 1 second
		        if (timer[2] >= 100):
		            timer[2] = 0
		            timer[1] += 1
		        # Every 60 seconds is equal to 1 min
        		if (timer[1] >= 60):
        		    timer[0] += 1
        		    timer[1] = 0
        		# We create our time string here
        		timeString = pattern.format(timer[0], timer[1], timer[2])
        		# Update the timeText Label box with the current time
        		timeText.configure(text=timeString)
        		# Call the update_timeText() function after 1 centisecond
   		    self.after(10, update_timeText)

		def starting():
			 personel = var_personel
			 #Personel ismi ve istasyon numarasını ayırma
			 station_number = int(personel[1])
			 station_name = personel[5:-2] 
			 #Seçilen Ürün Sayısı
			 product_counter = int(counter_variable.get())
			 #Json Values
    			 values = {'name' : station_name , 'product' : variable.get() , 'status' : 'prod_start' , 'station_number' : station_number ,'product_count' : product_counter}
   			 data_json = json.dumps(values)
			 
   			 if variable.get() != 'Urun Seciniz':
	 			 if product_counter != 1 :
					result = tkMessageBox.askquestion("3dPrinter",counter_variable.get() + " Adet "+ variable.get() + " Yazıcı Üretimine Başlanılsın mı?" ,icon='warning')
				 else :
   	 				result = tkMessageBox.askquestion("3dPrinter",variable.get() + " Yazıcı Üretimine Başlanılsın mı?" ,icon='warning')
  				 if result == 'yes':
					#Timer Start
					global state
    					state = True
					#Printer Counter
					for x in range(product_counter) : #Printer Sayısı Kadar Veri Post Edilecek.
						r = requests.post(url,data=values)
   						print r.text
					tkMessageBox.showinfo("3dPrinter",r.text) #Bir önceki istasyonda ürün olmaz ise hata yazısı görünecek.
					if r.text == 'Uretime Baslandi.' :
						#Framedeki widgetları silip yeni widgetlar eklendi.
						dropdownmenu.grid_remove()	
						dropdownmenu2.grid_remove()	
						printer_text.grid(row=20 , column=9 , padx = 9,pady=12)
						timeText.grid(row=20 , column=11 , padx = 60,pady=12)
						start_button.destroy()
						stop_button = Button(self,image=stop_photo, text="BİTİR", command=stopped, height=50, width=150, compound=LEFT, bg='white' , font = ('Sans','12','bold'))
	    					stop_button.place(x = 10, y = 90)
    			 else:
					message = tkMessageBox.showerror("Hata","Lütfen Ürün Seçimi Yapınız.")

		def stopped():
		    personel = var_personel
		    station_number = personel[1]
		    station_name = personel[5:-2]
		    values = {'name' : station_name , 'product' : variable.get() , 'status' : 'prod_stop' , 'station_number' : station_number}
		    data_json = json.dumps(values)
		    global state
		    state = False
		    result = tkMessageBox.askquestion("3dPrinter","Yazıcı Üretimi Tamamlandı mı?" ,icon='warning')
		    if result == 'yes':
				global timer
		   		timer = [0, 0, 0]
		    		timeText.configure(text='00:00:00')
				a = int(counter_variable.get())
				for x in range(a) :
					r = requests.post(url,data=values)
				tkMessageBox.showinfo("3dPrinter","Yazıcı Üretimi Tamamlandı. Teşekkürler :)")
				printer_text.grid_remove()
				timeText.grid_remove()		
				dropdownmenu.grid(row=20 , column=9 , padx = 9,pady=12)
				dropdownmenu2.grid(row=20 , column=10 , padx = 0,pady=12)

				stop_button.destroy()
				start_button = Button(self,image=start_photo, text="BAŞLA      ", command=starting, height=50, width=150, compound=LEFT, bg='white' , font = ('Sans','12','bold') )
				start_button.place(x = 10, y = 90)
		    else :
		    		state = True
		#---Background İmage----
		master_photo=PhotoImage(file="background.png")
		background_label = Label(self, image=master_photo)
		background_label.place(x=0, y=0, relwidth=1, relheight=1)
		background_label.image=master_photo
	
		update_timeText()
		#timer sıfırlanır.
		timeText = Label(self, text="00:00:00", font=("Sans", 20, 'bold'),bg='white')
		

		start_photo=PhotoImage(file="start.png")
		stop_photo=PhotoImage(file="stop.png")
		pause_photo = PhotoImage(file="pause.png")
		logo_photo = PhotoImage(file="logo.png")
		
		logo_label = Label(self, image=logo_photo)
		logo_label.image=logo_photo  #Photo References
		logo_label.place(x=200, y=110)
		
		#Printer Name Dropdown Menu
		variable = StringVar(self)
		variable.set("Urun Seciniz") # default value
		dropdownmenu = OptionMenu(self, variable, *PRINTERNAME)
		dropdownmenu["height"] = 2
		dropdownmenu["width"] = 16
		dropdownmenu["bg"] = 'white'
		dropdownmenu["font"] = ('Sans','12','bold')
		dropdownmenu.grid(row=20 , column=9 , padx = 9,pady=10)
		menu = dropdownmenu.nametowidget(dropdownmenu.menuname) 
		menu.configure(font=('Sans', 25, 'bold'), bg='white') 
		
		#Printer Counter Dropdown Menu
		counter_variable = StringVar(self)
		counter_variable.set("1") # default value
		dropdownmenu2 = OptionMenu(self, counter_variable, *COUNT)
		dropdownmenu2["height"] = 2
		dropdownmenu2["width"] = 3
		dropdownmenu2["bg"] = 'white'
		dropdownmenu2["font"] = ('Sans','12','bold')
		dropdownmenu2.grid(row=20 , column=10 , padx = 0,pady=10)
		menu = dropdownmenu2.nametowidget(dropdownmenu2.menuname) 
		menu.configure(font=('Sans', 25, 'bold'), bg='white') 

		start_button = Button(self,image=start_photo, text="BAŞLA      ", command = starting,  height=48, width=145, compound=LEFT, bg='white' , font = ('Sans','12','bold'))
		stop_button = Button(self,image=stop_photo, text="BİTİR        ", command = stopped, height=48, width=145, compound=LEFT, bg='white' , font = ('Sans','12','bold'))
		pause_button = Button(self,image=pause_photo, text="DURAKLAT", command = lambda:controller.show_frame(PausePage), height=48, width=145, compound=LEFT, bg='white' , font = ('Sans','12','bold'))
		quit_button = Button(self, text="Quit", command=quit, height=2, width=20, compound=LEFT)
		ekstra_button = Button(self,text = "Ekstra" , command = lambda:controller.show_frame(EkstraPage),height=1, width=5, compound=LEFT, bg='white' , font = ('Sans','12','bold') )

		start_button.image=start_photo #Photo References
		start_button.place(x = 10, y = 90)
		pause_button.image=pause_photo	#Photo References	
		pause_button.place(x = 10, y = 160)
		ekstra_button.place(x= 230 , y=70)

		printer_text = Label(self,textvariable =  variable ,font = "Sans 16 bold",bg='white')

class EkstraPage(Frame):
	def __init__(self,parent,controller):
		Frame.__init__(self, parent)
		def personel_menu():
			result = tkMessageBox.askquestion("3dPrinter","Bir Önceki Personel Menüsüne Dönmek İstiyor musunuz ?" ,icon='warning')
		    	if result == 'yes':
				controller.show_frame(LoginPage)
				
			
		def paketleme():
			personel = var_personel
			station_number = int(personel[1])
			station_name = personel[5:-2] 
			print (station_name)
	
    			values = {'name' : station_name , 'product' : variable.get() , 'status' : 'prod_paketleme' , 'station_number' : station_number}
   			data_json = json.dumps(values)

			if variable.get() != 'Urun Seciniz':
				 product_counter = int(counter_variable.get())
	 			 if product_counter != 1 :
					result = tkMessageBox.askquestion("3dPrinter",counter_variable.get() + " Adet "+ variable.get() + " Yazıcı Kargoya Verildi mi?" ,icon='warning')
				 else :
   	 				result = tkMessageBox.askquestion("3dPrinter",variable.get() + " Yazıcı Kargoya Verildi mi?" ,icon='warning')
  				 if result == 'yes':
					for x in range(product_counter) :
						r = requests.post(url,data=values)
   						print r.text
						
					tkMessageBox.showinfo("3dPrinter","Yazıcı Kargoya Verildi Bilgisi İletildi. :)")
    			else:
					message = tkMessageBox.showerror("Hata","Lütfen Ürün Seçimi Yapınız.")

		def ekstra_cikis():
			result = tkMessageBox.askquestion("3dPrinter","Çıkış Yapmak İstediğinize Eminmisiniz ?" ,icon='warning')
	   		if result == 'yes':
				controller.show_frame(MasterPage)
				tkMessageBox.showinfo("3dPrinter"," Çıkış Yapıldı. :)")
			else:
				tkMessageBox.showinfo("3dPrinter","İşlem İptal Edildi.")
				
		
		ekstra_photo=PhotoImage(file="background.png")
		background_label = Label(self, image=ekstra_photo)
		background_label.place(x=0, y=0, relwidth=1, relheight=1)
		background_label.image=ekstra_photo

		personel_button = Button(self, text="Personel Seçimi Sayfasına Geri Dön.", command=personel_menu, height=2, width=40, compound=LEFT, bg='white', font = ('Sans','8','bold'))
		paketleme_button = Button(self, text="Kargoya Verilen Ürünü Bildir.", command=paketleme, height=2, width=30, compound=LEFT, bg='white', font = ('Sans','8','bold'))
		cikis_button = Button(self, text="Çıkış", command=ekstra_cikis, height=2, width=40, compound=LEFT, bg='white', font = ('Sans','8','bold'))
		cikis_program = Button(self, text="Programdan Çıkış", command=quit, height=1, width=40, compound=LEFT, bg='white', font = ('Sans','8','bold'))
		personel_button.place(x = 5, y = 5)
		paketleme_button.place(x = 35, y = 115)
		cikis_button.place(x = 5, y = 160)
		cikis_program.place(x = 5, y = 210)
		
		#Dropdown Menu
		variable = StringVar(self)
		variable.set("Urun Seciniz") # default value
		dropdownmenu = OptionMenu(self, variable, *PRINTERNAME)
		dropdownmenu["height"] = 2
		dropdownmenu["width"] = 16
		dropdownmenu["bg"] = 'white'
		dropdownmenu["font"] = ('Sans','12','bold')
		dropdownmenu.grid(row=20 , column=9 , padx = 7,pady=55)
		menu = dropdownmenu.nametowidget(dropdownmenu.menuname) 
		menu.configure(font=('Sans', 25, 'bold'), bg='white') 
		
		#Dropdown Menu
		counter_variable = StringVar(self)
		counter_variable.set("1") # default value
		dropdownmenu2 = OptionMenu(self, counter_variable, *COUNT)
		dropdownmenu2["height"] = 2
		dropdownmenu2["width"] = 3
		dropdownmenu2["bg"] = 'white'
		dropdownmenu2["font"] = ('Sans','12','bold')
		dropdownmenu2.grid(row=20 , column=10 , padx = 0,pady=55)
		menu = dropdownmenu2.nametowidget(dropdownmenu2.menuname) 
		menu.configure(font=('Sans', 25, 'bold'), bg='white')
		
		
class PausePage(Frame):
	def __init__(self,parent,controller):
		Frame.__init__(self, parent)
		
		
		def pause_mola():
			personel = var_personel
			station_number = personel[1]
			station_name = personel[5:-2]
			values = {'name' : station_name , 'status' : 'prod_mola' , 'station_number' : station_number}
    			data_json = json.dumps(values)
			result = tkMessageBox.askquestion("Printer","Mola Vermek İstediğinize Emin misiniz?" ,icon='warning')
   			if result == 'yes':
				r = requests.post(url,data=values)
   		        	print r.text
				tkMessageBox.showinfo("Printer","İletildi. :)")
				controller.show_frame(MasterPage)
			else:
				tkMessageBox.showinfo("Printer","İşlem İptal Edildi.")
				
		def pause_parca():
			personel = var_personel
			station_number = personel[1]
			station_name = personel[5:-2]
			values = {'name' : station_name , 'status' : 'prod_parca', 'station_number' : station_number }
	    		data_json = json.dumps(values)
			result = tkMessageBox.askquestion("Printer","Parça İsteğini İletmek İstediğinize Emin misiniz?" ,icon='warning')
	   		if result == 'yes':
				r = requests.post(url,data=values)
	   	        	print r.text
				tkMessageBox.showinfo("Printer"," İletildi. :)")
				controller.show_frame(MasterPage)
			else:
				tkMessageBox.showinfo("Printer","İşlem İptal Edildi.")
		def pause_ariza():
			personel = var_personel
			station_number = personel[1]
			station_name = personel[5:-2]
			values = {'name' : station_name , 'status' : 'prod_ariza' , 'station_number' : station_number}
	    		data_json = json.dumps(values)
			result = tkMessageBox.askquestion("Printer","Arıza Bildirisini İletmek İstediğinize Emin misiniz?" ,icon='warning')
	   		if result == 'yes':
				r = requests.post(url,data=values)
	   	        	print r.text
				tkMessageBox.showinfo("Printer"," İletildi. :)")
				controller.show_frame(MasterPage)
			else:
				tkMessageBox.showinfo("3dPrinter","İşlem İptal Edildi.")
		def pause_teknik():
			personel = var_personel
			station_number = personel[1]
			station_name = personel[5:-2]
			values = {'name' : station_name , 'status' : 'prod_teknik' , 'station_number' : station_number}
	    		data_json = json.dumps(values)
			result = tkMessageBox.askquestion("3dPrinter","Teknik İşleri İletmek İstediğinize Emin misiniz?" ,icon='warning')
	   		if result == 'yes':
				r = requests.post(url,data=values)
	   	        	print r.text
				tkMessageBox.showinfo("3dPrinter"," İletildi. :)")
				controller.show_frame(MasterPage)
			else:
				tkMessageBox.showinfo("3dPrinter","İşlem İptal Edildi.")
		def pause_cikis():
			result = tkMessageBox.askquestion("3dPrinter","Çıkış Yapmak İstediğinize Eminmisiniz ?" ,icon='warning')
	   		if result == 'yes':
				controller.show_frame(MasterPage)
				tkMessageBox.showinfo("3dPrinter"," Çıkış Yapıldı. :)")
			else:
				tkMessageBox.showinfo("3dPrinter","İşlem İptal Edildi.")

		pause_photo=PhotoImage(file="background.png")
		background_label = Label(self, image=pause_photo)
		background_label.place(x=0, y=0, relwidth=1, relheight=1)
		background_label.image=pause_photo

		#Pause Button Screen
		
		mola_button = Button(self, text="Mola Verildi.", command=pause_mola, height=2, width=40, compound=LEFT, bg='white', font = ('Sans','8','bold'))
		parca_button = Button(self, text="Yazıcı Üretimi İçin Yeteri Kadar Parça Bulunmadı.", command=pause_parca, height=2, width=40, compound=LEFT, bg='white', font = ('Sans','8','bold'))
		ariza_button = Button(self, text="Arızalı Makineyle İlgileniliyor.", command=pause_ariza, height=2, width=40, compound=LEFT, bg='white', font = ('Sans','8','bold'))
		teknik_button = Button(self, text="Teknik İşler Yapılıyor(Üretime Ön Hazırlık vb).", command=pause_teknik, height=2, width=40, compound=LEFT, bg='white', font = ('Sans','8','bold'))
		cikis_button = Button(self, text="Çıkış", command=pause_cikis, height=2, width=40, compound=LEFT, bg='white', font = ('Sans','8','bold'))
		
		mola_button.place(x = 5, y = 5)
		parca_button.place(x = 5, y = 50)
		ariza_button.place(x = 5, y = 95)
		teknik_button.place(x = 5, y = 140)
		cikis_button.place(x = 5, y = 195)
			
app = ProductionPlanning()
app.mainloop()
