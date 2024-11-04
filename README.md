# Fuvarozó rendszer megvalósítása Laravel keretrendszerben

Készíts egy egyszerű fuvarozó rendszert Laravelben, ahol a cég
adminisztrátora új munkákat hozhat létre, és azokat fuvarozókhoz rendelheti. 
Minden fuvarozónak egy járműve lehet, és több munkát végezhet egy időben. A feladat fő célja a
backend funkcionalitás kidolgozása, a frontend lehet egyszerű, minimalista, az
adminisztrációs műveletek kezelése a fő hangsúly.

### 1. Felhasználói szerepkörök
- A rendszer két fő felhasználói szerepkört tartalmaz:
    - Adminisztrátor: Képes munkákat létrehozni, módosítani, törölni és fuvarozókhoz rendelni.
    - Fuvarozó: Megtekintheti a neki kiosztott munkákat, és frissítheti azok státuszát.

### 2. Entitások és kapcsolatok
- Fuvarozó:
    - Név
    - E-mail cím
    - Jelszó (regisztráció/authentication céljából)
- Jármű:
    - Márka
    - Típus
    - Rendszám
    - Fuvarozóhoz rendelve (kapcsolat a fuvarozóval)
- Munka:
    - Kiindulási cím
    - Érkezési cím
    - Címzett neve
    - Címzett telefonszáma
    - Státusz: (Kiosztva, Folyamatban, Elvégezve, Sikertelen)
    - Megjegyzés (sikertelen kézbesítés esestén)
    - Fuvarozóhoz rendelve (kapcsolat a fuvarozóval)

### 3. Funkciók

- Adminisztrátor funkciói:
    - Munkák létrehozása: Az adminisztrátor létrehozhat új fuvarfeladatokat, melyek tartalmazzák a kiindulási címet, érkezési címet, címzett nevét és elérhetőségét.
    - Munkák módosítása: Munkák adatai (pl. címek, címzett) módosíthatók az adminisztrátor által.
    - Munkák törlése: Adminisztrátor törölhet munkákat a rendszerből.
    - Munkák fuvarozókhoz rendelése: Az adminisztrátor a létrehozott munkákat fuvarozókhoz rendelheti.
    - Járművek létrehozása
    - Járművek szerkesztése
    - Járművek törlése: Egy járművet csak addig lehet törölni, ameddig nincs fuvarozóhoz kötve.
    - Járművek fuvarozókhoz rendelése: Egy fuvarozó nem kaphat munkát, ameddig nincsen jármnűve.

- Fuvarozó funkciói:
    - Munkák megtekintése: Fuvarozók megtekinthetik a nekik kiosztott munkákat, azok státuszát, valamint a címzett adatait.
    - Munkák státuszának módosítása: A fuvarozó a neki kiosztott munka státuszát tudja frissíteni:
        - Kiosztva
        - Folyamatban
        - Elvégezve
        - Sikertelen (pl. a címzett nem volt elérhető)

### 4. Bónusz funkciók
- Státusz alapú szűrés: Az adminisztrátor szűrheti a munkákat a státuszuk szerint.
- Értesítések: Az adminisztrátor értesítést kaphat, ha egy munka sikertelen lett.
- API végpontok:
    - Stateless
        - /login
        - /logout
        - /jobs
            - A fuvarozó megkapja a saját munkáit, adminisztrátor pedig az összeset.
            - Adminisztrátor módosíthat, illetve létrehozhat munkákat.
    - Stateful
        - /dismiss_message/{id}
            - Bizonyos üzenet törlése.
        - /read_messages
            - Üzenetek elolvasása.

### 5. Tesztelés
- A projekthez készült egy pár unit és feature teszt is.
