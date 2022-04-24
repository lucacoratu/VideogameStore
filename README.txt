Coratu Luca-Ionut

Tema proiectului este magazin online de jocuri video, asemanator cu steam.

In pagina principala (index.html) am pus elementele pentru toate tipurile de conturi la care m-am
gandit ca sa poata fi accesate usor intrucat nu am lucrat inca la partea de backend.
Navbar-ul de sus contine un link catre pagina principala, un dropdown care in momentul de fata este doare de design,
link-urile din cadrul acestuia nu duc catre ceva. In acest dropdown as vrea sa pun link-uri catre pagini cu anumite jocuri
care se sunt reduse intr-o perioada sau recomandari in functie de rating si tag-uri pe care le utilizatorul le-a mai cumparat.
Ce mai este de mentionat legat de navbar este ca acestea vor disparea/aparea in functie de tipul de cont, iar in momentul micsorarii
browser-ului acestea se rastrang intr-un meniu dropdown (2 variante: fie toate 5 dispar, fie doar 3 si 2 raman).
In plus, searchbar-ul de sus permite listarea jocurilor care contin in titlu textul introdus.

In partea centrala a paginii se afla o listare a jocurilor, sub forma de grid cu template de coloane
care se adapteaza la dimensiunea ferestrei. Fiecare joc in parte contine o poza, o scurta descriere (partea stanga a pozei)

In partea de jos este un footer care contine date de contact.

Am adaugat la store un link catre library, unde vor fi afisate jocurile detinute de contul respectiv. Fiecare joc din lista poate fi apasat
si va aparea pagina jocului.

Fiecare joc din pagina principala poate fi apasat si va duce catre o pagina template, in care se afla mai multe detalii despre acesta.
In aceasta pagina cu detalii se regaseste un player de video, si o insiruire de poze si videoclipuri sub acesta, care pot fi 
selectate si acestea vor aparea in chenarul de deasupra.
In dreapta acestui player se regaseste descrierea jocului cat si o lista de tag-uri (categoria din care face parte jocul). Sub acestea,
mai apar cateva detalii legate de joc: rating, data aparitiei, developer cat si cel care a publicat.

Tot in aceasta pagina apare si pretul produsului. In continuare mai sunt cateva detalii de forma titlu - paragraf, descrierea developerului
si cerintele de sistem.

Pagina de login este una simpla care contine un form centrat in pagina cu input pentru username si parola
si un checkbox pentru retinerea contului. Se mai regaseste si o referinta catre pagina de register.

Pagina de register contine un multi-form in care sunt cerute diferite detalii referitoare la contul care sa fie creat.
Sursa de inspiratie pentru acesti multi-form este urmatoarul videoclip: https://www.youtube.com/watch?v=VdqtdKXxKhM.
In cadrul acestui form sunt si cateva campuri (la step 3) care nu sunt obligatorii si anume detalii legate de conturi pe
alte retele de socializare.

Pagina de profil este simplista si are o afisare a datelor contului (momentan hardcodate) sub forma de tabel.
Inca un feature pe care l-am pus aici este adaugarea de fonduri deoarece este nevoie ca un utilizator sa poata 
adauga fonduri ca sa poata cumpara jocuri.

Cosul de cumparaturi este si el simplist si contine poza jocului, numele si pretul acestuia, ma gandeam ca acest cos sa fie salvat in baza
de date pentru a fi pastrat daca browserul este inchis si de aceea momentan adaugarea, stergerea si calculul total al cosului nu sunt functionale.

Pagina catre care duce butonul de create din pagina principala as dori sa fie o functionalitate a conturilor de administrator, si anume sa poata introduce
jocuri si toate detaliile aferente acestora in baza de date. Aceasta pagina contine un formular in care pot fi introduse input-uri de diferite feluri:
imagini, video-uri, date, text etc. Tot aici mai sunt si doua butoane pentru adaugarea de detalii la joc sau stergerea ultimului detaliu. Acestea sunt pentru zona de more details
din pagina jocului.

Imaginile folosite in cadrul design-ului se gasesc in directorul Resources.
Styling-ul fiecarei pagini se afla in numepagina.css mai putin la pagina principala, la care
codul css se afla in homestyle.css. La scripturi acestea se afla in numepagina.js

Cateva cuvinte legate de tipurile de conturi la care m-am gandit:
Guest - ma gandeam sa fie atunci cand nu este conectat la niciun cont. Acesta nu va avea inregistrare in baza de date.
Pentur acesta in navbar vor fi doar butoanele de home, login si sign up.
Normal - acesta va avea acces la toate butoanele din navbar mai putin create
Admin - acesta va avea acces si la create (unde se vor introduce date despre jocuri care va fi introdus apoi in baza de date).

Ce este implementat pana acum din partea de backend: 
    - conectarea la un cont de utilizator
    - inregistrarea unui nou cont de utilizator (Normal)
    - delogarea de la un cont de utilizator.
    - listarea jocurilor in pagina principala
    - butoanele care apar in navbar sunt diferite in functie de tipul de cont.
    - pagina produsului este diferita pentru fiecare in parte.
    - adaugarea unui joc in baza de date din pagina de create

Un cont de admin care poate fi utilizat pentru testare:
username: temalaborator
password: 12345


Surse de inspiratie: 

Multiform: https://www.youtube.com/watch?v=VdqtdKXxKhM

Pentru meniul de navigatie am gasit urmatorul tutorial pe care l-am adaptat la ce am vrut eu sa fac:
    https://www.w3schools.com/howto/howto_css_dropdown_navbar.asp
