# 🐳 Docker Hosting Guide (Interior Touch)

Agar aap apne project ko **Docker** par host karna chahte hain, toh ye guide follow karein. Ye setup production-ready hai aur isme **PHP 8.4**, **Apache**, aur **MariaDB** shamil hain.

---

### **1. Minimum Server Requirements**
*   **OS:** Ubuntu 22.04 LTS (Recommended)
*   **RAM:** 2 GB Minimum (Smooth experience ke liye)
*   **CPU:** 2 vCPU
*   **Disk:** 20 GB+ SSD

---

### **2. Server Par Docker Install Karein**
Server login karne ke baad, pehle ye command run karein Docker install karne ke liye:

```bash
# Docker install karne ke liye
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Docker Compose install karne ke liye
sudo apt-get install -y docker-compose
```

---

### **3. Project Setup (First Time)**
Pehle apne server par code download karein aur environment file setup karein.

```bash
# Code clone karein
git clone https://github.com/kriziatech/client-chart-system.git
cd client-chart-system

# Environment file create karein
cp .env.example .env

# Permissions fix karein (Script ke liye)
chmod +x deploy-docker.sh
```

**Note:** `.env` file mein apni **DB_PASSWORD** aur **APP_URL** change kar lena.

---

### **4. Deployment (The Magic Command)**
Har baar jab aapko update push karna ho ya pehli baar setup karna ho, bas ye command run karein:

```bash
./deploy-docker.sh
```

**Is script se ye sab automatic hoga:**
1. Git se latest code aayega.
2. Docker images build hongi.
3. Database migrations chalengi.
4. Cache optimize hoga.
5. Project live ho jayega.

---

### **5. Important Ports**
*   **Main Application:** `http://your-server-ip:8081`
*   **Dashboard access:** Login with your admin credentials.

---

### **6. Maintenance Commands**
*   **Logs dekhne ke liye:** `docker-compose logs -f app`
*   **Containers stop karne ke liye:** `docker-compose down`
*   **Database backup:** `docker-compose exec db mysqldump -u root -psecret client_chart > backup.sql`

---

Kaam ho gaya! Aapka system ab Docker containers ke andar safely run karega. 🚀
