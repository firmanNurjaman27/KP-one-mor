// MCEAT LEARNING SYSTEM - With PHP Backend

let player = {
    id: playerData.id,
    name: playerData.name,
    level: playerData.level,
    xp: playerData.xp,
    completedMateri: playerData.completedMateri,
    badges: playerData.badges,
    game: { score: 0, maxScore: 6, bestCombo: 0 },
    quiz: { bestScore: playerData.bestQuizScore, attempts: 0 },
    exploreProgress: playerData.exploreProgress,
    dailyMissions: { read: false, explore: false, game: false, quiz: false }
};

const badgesData = [
    { id: "pemula_halal", name: "🌱 Pemula Halal", icon: "fas fa-seedling", condition: () => player.xp >= 50 },
    { id: "pembaca_halal", name: "📖 Pembaca Halal", icon: "fas fa-book", condition: () => Object.values(player.completedMateri).filter(v=>v===true).length >= 4 },
    { id: "game_master", name: "🎮 Game Master", icon: "fas fa-gamepad", condition: () => player.game.score >= 6 },
    { id: "juara_kuis", name: "🏆 Juara Kuis", icon: "fas fa-trophy", condition: () => player.quiz.bestScore >= 12 },
    { id: "penjelajah", name: "🗺️ Penjelajah Halal", icon: "fas fa-compass", condition: () => Object.values(player.exploreProgress).filter(v=>v===true).length >= 6 },
    { id: "taat_allah", name: "💖 Pencinta Halal", icon: "fas fa-heart", condition: () => player.xp >= 250 },
    { id: "mceat_master", name: "⭐ Master MCEAT", icon: "fas fa-star", condition: () => player.level >= 5 }
];

const explorePillars = [
    { id: "makanan", name: "Makanan Halal", icon: "🍗", desc: "Makanan yang diizinkan Allah: hewan disembelih dengan nama Allah, makanan laut, buah & sayur." },
    { id: "minuman", name: "Minuman Halal", icon: "🥤", desc: "Minuman yang halal: air putih, susu, jus buah, dan minuman tidak memabukkan." },
    { id: "perbuatan", name: "Perbuatan Halal", icon: "🤲", desc: "Perbuatan baik: shalat, puasa, jujur, menolong sesama, bekerja halal." },
    { id: "pakaian", name: "Pakaian Halal", icon: "👗", desc: "Pakaian yang menutup aurat, tidak transparan, tidak menyerupai lawan jenis." },
    { id: "muamalah", name: "Muamalah Halal", icon: "💰", desc: "Jual beli yang halal: tidak ada riba, gharar (tipuan), dan barang halal." },
    { id: "ibadah", name: "Ibadah Halal", icon: "🕌", desc: "Ibadah yang dilakukan sesuai tuntunan Rasulullah SAW." }
];

let gameCards = [], selectedIndex = null, matchedCount = 0, attempts = 0, combo = 0;
let currentQuizIndex = 0, quizScore = 0, quizAnswered = false;

document.addEventListener('DOMContentLoaded', () => {
    updateUI();
    setupEventListeners();
    renderGame();
    renderAchievements();
    renderExplorePillars();
    updateDailyMissionsUI();
    loadDailyMissions();
});

async function apiCall(action, data = {}) {
    try {
        const response = await fetch('api/save_data.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action, player_id: player.id, ...data })
        });
        return await response.json();
    } catch (error) {
        console.error('API Error:', error);
        return { success: false };
    }
}

function updateUI() {
    document.getElementById('displayName').innerText = player.name;
    document.getElementById('levelValue').innerText = player.level;
    document.getElementById('xpValue').innerText = player.xp;
    document.getElementById('xpBarFill').style.width = `${player.xp % 100}%`;
    
    let materiComplete = Object.values(player.completedMateri).filter(v=>v===true).length;
    let totalMateri = document.querySelectorAll('.materi-card-modern').length || 4;
    document.getElementById('materiCount').innerText = `${materiComplete}/${totalMateri}`;
    document.getElementById('gameStat').innerText = `${player.game.score}/6`;
    document.getElementById('quizStat').innerText = `${player.quiz.bestScore}/15`;
    document.getElementById('totalXPAchieve').innerText = player.xp;
    
    let globalPercent = Math.floor(((materiComplete/totalMateri) + (player.game.score/6) + (player.quiz.bestScore/15)) / 3 * 100);
    document.getElementById('globalPercent').innerText = globalPercent;
    
    let unlockedBadges = badgesData.filter(b=>b.condition()).length;
    document.getElementById('badgeCount').innerText = unlockedBadges;
}

function showNotification(title, message, icon = "🎉") {
    const modal = document.getElementById('notificationModal');
    if (modal) {
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalMessage').innerHTML = message;
        document.getElementById('modalIcon').innerText = icon;
        modal.style.display = 'flex';
        setTimeout(() => { modal.style.display = 'none'; }, 3000);
    }
}

function closeNotificationModal() { 
    document.getElementById('notificationModal').style.display = 'none'; 
}

async function addXP(amount, source = "") {
    player.xp += amount;
    let newLevel = Math.floor(player.xp / 100) + 1;
    if (newLevel > player.level) {
        player.level = newLevel;
        showNotification("🎉 LEVEL UP! 🎉", `Selamat! Kamu naik ke Level ${player.level}!`, "🌟");
    }
    updateUI();
    checkBadges();
    await apiCall('add_xp', { amount, source });
}

function checkBadges() {
    badgesData.forEach(async badge => {
        if (badge.condition() && !player.badges.includes(badge.id)) {
            player.badges.push(badge.id);
            showNotification("🏅 Lencana Baru! 🏅", `Kamu mendapatkan lencana "${badge.name}"!`, badge.icon);
            renderAchievements();
            await apiCall('add_badge', { badge_id: badge.id });
        }
    });
}

function renderAchievements() {
    const container = document.getElementById('badgesGrid');
    if (!container) return;
    container.innerHTML = '';
    badgesData.forEach(badge => {
        const isUnlocked = player.badges.includes(badge.id);
        const badgeDiv = document.createElement('div');
        badgeDiv.className = `badge-card ${!isUnlocked ? 'locked' : ''}`;
        badgeDiv.innerHTML = `<i class="${badge.icon}"></i><div class="badge-name">${badge.name}</div>${!isUnlocked ? '<small style="color:rgba(255,255,255,0.5)">🔒 Belum Terbuka</small>' : '<small style="color:#FFD700">✓ Sudah Dimiliki</small>'}`;
        container.appendChild(badgeDiv);
    });
}

function setupEventListeners() {
    document.querySelectorAll('.nav-item').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.nav-item').forEach(b=>b.classList.remove('active'));
            btn.classList.add('active');
            const section = btn.dataset.section;
            document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
            document.getElementById(`${section}Section`).classList.add('active');
        });
    });
    
    document.getElementById('startAdventureBtn')?.addEventListener('click', () => {
        document.querySelector('.nav-item[data-section="materi"]').click();
    });
    
    document.querySelectorAll('.materi-header').forEach(header => {
        header.addEventListener('click', () => {
            const card = header.closest('.materi-card-modern');
            card.classList.toggle('open');
        });
    });
    
    document.querySelectorAll('.btn-complete:not(:disabled)').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.stopPropagation();
            const materiId = btn.dataset.materi;
            const xpAmount = parseInt(btn.dataset.xp);
            
            if (!player.completedMateri[materiId]) {
                player.completedMateri[materiId] = true;
                await addXP(xpAmount, `Materi ${materiId}`);
                await apiCall('complete_materi', { materi_id: materiId, xp_reward: xpAmount });
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-check-circle"></i> Selesai!';
                btn.style.opacity = '0.7';
                updateUI();
                
                if (Object.values(player.completedMateri).filter(v=>v===true).length >= 4) {
                    const badge = document.getElementById('materiCompleteBadge');
                    if (badge) badge.style.display = 'flex';
                    checkBadges();
                }
                
                if (!player.dailyMissions.read && Object.values(player.completedMateri).filter(v=>v===true).length >= 4) {
                    player.dailyMissions.read = true;
                    await addXP(80, "Daily Quest: Membaca Materi");
                    await apiCall('daily_mission', { mission_type: 'read' });
                    updateDailyMissionsUI();
                }
            }
        });
    });
    
    document.getElementById('resetGameBtn')?.addEventListener('click', () => resetGame());
    document.getElementById('nextQuizBtn')?.addEventListener('click', () => {
        currentQuizIndex++;
        loadQuizQuestion();
    });
}

function renderGame() {
    gameCards = shuffleArray([...gamePairsData]);
    selectedIndex = null;
    matchedCount = player.game.score;
    attempts = 0;
    combo = 0;
    updateGameUI();
    renderGameBoard();
}

function shuffleArray(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
}

function renderGameBoard() {
    const board = document.getElementById('gameBoard');
    if (!board) return;
    board.innerHTML = '';
    gameCards.forEach((card, idx) => {
        const cardDiv = document.createElement('div');
        cardDiv.className = 'game-card';
        if (card.matched) cardDiv.classList.add('matched');
        if (selectedIndex === idx) cardDiv.classList.add('selected');
        cardDiv.innerHTML = `<div style="font-size:32px;margin-bottom:8px;">${card.icon || (card.category==='kategori'?'⭐':'📌')}</div><div style="font-size:13px;">${card.text_content}</div>`;
        cardDiv.onclick = () => handleCardClick(idx);
        board.appendChild(cardDiv);
    });
}

async function handleCardClick(idx) {
    if (gameCards[idx].matched) return;
    if (selectedIndex === null) {
        selectedIndex = idx;
        renderGameBoard();
        return;
    }
    attempts++;
    const card1 = gameCards[selectedIndex];
    const card2 = gameCards[idx];
    if (card1.match_id === card2.match_id && card1.id !== card2.id) {
        card1.matched = true;
        card2.matched = true;
        matchedCount++;
        combo++;
        player.game.score = matchedCount;
        let xpGain = 15 + Math.min(combo * 2, 20);
        await addXP(xpGain, "Game Matching");
        showGameFeedback("🎉 Cocok! +" + xpGain + " XP", "success");
        if (combo > player.game.bestCombo) player.game.bestCombo = combo;
        if (matchedCount === 6) {
            showGameFeedback("🏆 SEMPURNA! Kamu menyelesaikan semua pasangan! 🏆", "success");
            await apiCall('update_game_score', { score: matchedCount, attempts, combo });
            if (!player.dailyMissions.game) {
                player.dailyMissions.game = true;
                await addXP(60, "Daily Quest: Game");
                await apiCall('daily_mission', { mission_type: 'game' });
                updateDailyMissionsUI();
            }
            checkBadges();
        }
    } else {
        combo = 0;
        showGameFeedback("❌ Tidak cocok! Coba lagi ya!", "error");
    }
    selectedIndex = null;
    updateGameUI();
    renderGameBoard();
}

function updateGameUI() {
    document.getElementById('gameScore').innerText = player.game.score;
    document.getElementById('gameAttempts').innerText = attempts;
    document.getElementById('comboCount').innerText = combo;
}

function showGameFeedback(msg, type) {
    const msgDiv = document.querySelector('#gameFeedback .feedback-message');
    if (msgDiv) {
        msgDiv.innerText = msg;
        msgDiv.style.color = type === 'success' ? '#28C76F' : '#FF6584';
        setTimeout(() => { msgDiv.innerText = ''; }, 2000);
    }
    const bubble = document.getElementById('mascotBubble');
    if (bubble) {
        bubble.innerText = msg;
        setTimeout(() => { bubble.innerText = "Ayo lanjut belajar ya! 💪"; }, 2500);
    }
}

async function resetGame() {
    player.game.score = 0;
    matchedCount = 0;
    gameCards.forEach(card => delete card.matched);
    renderGame();
    updateUI();
    await apiCall('reset_game');
}

function loadQuizQuestion() {
    if (currentQuizIndex >= quizData.length) { finishQuiz(); return; }
    const q = quizData[currentQuizIndex];
    document.getElementById('quizQuestion').innerText = q.question;
    document.getElementById('currentQuestion').innerText = currentQuizIndex + 1;
    document.getElementById('totalQuestions').innerText = quizData.length;
    document.getElementById('quizScoreHeader').innerText = quizScore;
    document.getElementById('quizProgressFill').style.width = `${(currentQuizIndex / quizData.length) * 100}%`;
    const optionsDiv = document.getElementById('quizOptions');
    optionsDiv.innerHTML = '';
    const options = [q.option_a, q.option_b, q.option_c, q.option_d];
    options.forEach((opt, idx) => {
        const optDiv = document.createElement('div');
        optDiv.className = 'quiz-option';
        optDiv.innerText = opt;
        optDiv.onclick = () => checkQuizAnswer(idx);
        optionsDiv.appendChild(optDiv);
    });
    document.getElementById('nextQuizBtn').style.display = 'none';
    document.getElementById('quizCard').style.display = 'block';
    document.getElementById('quizResult').style.display = 'none';
    quizAnswered = false;
}

async function checkQuizAnswer(selectedIdx) {
    if (quizAnswered) return;
    quizAnswered = true;
    const q = quizData[currentQuizIndex];
    const isCorrect = (selectedIdx === q.correct_answer);
    const options = document.querySelectorAll('.quiz-option');
    options.forEach((opt, idx) => {
        if (idx === q.correct_answer) opt.classList.add('correct');
        if (idx === selectedIdx && !isCorrect) opt.classList.add('wrong');
    });
    if (isCorrect) {
        quizScore++;
        await addXP(10, "Kuis");
        showQuizResult("✅ Benar! +10 XP", "correct");
    } else {
        const correctText = q[`option_${String.fromCharCode(97 + q.correct_answer)}`];
        showQuizResult(`❌ Kurang tepat. Jawaban: ${correctText}`, "wrong");
    }
    document.getElementById('nextQuizBtn').style.display = 'block';
}

function showQuizResult(msg, type) {
    const resultDiv = document.getElementById('quizResult');
    resultDiv.style.display = 'block';
    resultDiv.innerHTML = `<div style="padding:12px;border-radius:16px;background:${type==='correct'?'rgba(40,199,111,0.2)':'rgba(244,67,54,0.2)'};color:${type==='correct'?'#28C76F':'#FF6584'}">${msg}</div>`;
}

async function finishQuiz() {
    document.getElementById('quizCard').style.display = 'none';
    document.getElementById('nextQuizBtn').style.display = 'none';
    if (quizScore > player.quiz.bestScore) {
        player.quiz.bestScore = quizScore;
        await apiCall('update_quiz_score', { score: quizScore });
        checkBadges();
    }
    const resultDiv = document.getElementById('quizResult');
    resultDiv.style.display = 'block';
    resultDiv.innerHTML = `<div style="text-align:center;padding:20px;"><i class="fas fa-trophy" style="font-size:60px;color:#FFC107;"></i><h3 style="color:white;margin:15px 0;">Kuis Selesai!</h3><p style="color:rgba(255,255,255,0.8);">Skor kamu: ${quizScore} dari ${quizData.length}</p><p style="color:#FFC107;">✨ Kamu mendapat ${quizScore * 5} XP tambahan! ✨</p><button onclick="restartQuiz()" class="quiz-next" style="margin-top:20px;">Ulang Kuis</button></div>`;
    await addXP(quizScore * 5, "Bonus Penyelesaian Kuis");
    if (!player.dailyMissions.quiz && quizScore >= 10) {
        player.dailyMissions.quiz = true;
        await addXP(70, "Daily Quest: Kuis");
        await apiCall('daily_mission', { mission_type: 'quiz' });
        updateDailyMissionsUI();
    }
    updateUI();
}

function restartQuiz() {
    currentQuizIndex = 0;
    quizScore = 0;
    quizAnswered = false;
    loadQuizQuestion();
}

function renderExplorePillars() {
    const container = document.getElementById('pillarsContainer');
    if (!container) return;
    container.innerHTML = '';
    explorePillars.forEach(pillar => {
        const isExplored = player.exploreProgress[pillar.id];
        const pillarDiv = document.createElement('div');
        pillarDiv.className = `pillar-card ${isExplored ? 'explored' : ''}`;
        pillarDiv.innerHTML = `<div class="pillar-icon">${pillar.icon}</div><div class="pillar-name">${pillar.name}</div>${isExplored ? '<small>✓ Dijelajahi</small>' : ''}`;
        pillarDiv.onclick = () => explorePillar(pillar.id, pillar.name, pillar.desc, pillar.icon);
        container.appendChild(pillarDiv);
    });
}

async function explorePillar(id, name, desc, icon) {
    document.getElementById('exploreIcon').innerHTML = icon;
    document.getElementById('exploreTitle').innerText = name;
    document.getElementById('exploreDesc').innerHTML = desc;
    if (!player.exploreProgress[id]) {
        player.exploreProgress[id] = true;
        await addXP(10, `Eksplorasi: ${name}`);
        await apiCall('explore', { explore_id: id });
        renderExplorePillars();
        const xpDiv = document.getElementById('exploreXp');
        if (xpDiv) {
            xpDiv.style.display = 'block';
            setTimeout(() => { xpDiv.style.display = 'none'; }, 3000);
        }
        if (Object.values(player.exploreProgress).filter(v=>v===true).length >= 6) {
            const achievementDiv = document.getElementById('exploreAchievement');
            if (achievementDiv) {
                achievementDiv.style.display = 'block';
                await addXP(50, "Bonus Eksplorasi Lengkap");
                if (!player.dailyMissions.explore) {
                    player.dailyMissions.explore = true;
                    await addXP(50, "Daily Quest: Eksplorasi");
                    await apiCall('daily_mission', { mission_type: 'explore' });
                    updateDailyMissionsUI();
                }
                setTimeout(() => { achievementDiv.style.display = 'none'; }, 5000);
            }
        }
        checkBadges();
    }
}

async function loadDailyMissions() { 
    updateDailyMissionsUI(); 
}

function updateDailyMissionsUI() {
    const missions = ['read', 'explore', 'game', 'quiz'];
    missions.forEach(mission => {
        const missionCard = document.querySelector(`.mission-card[data-mission="${mission}"]`);
        const checkSpan = document.getElementById(`mission${mission.charAt(0).toUpperCase() + mission.slice(1)}`);
        if (player.dailyMissions[mission]) {
            if (missionCard) missionCard.classList.add('completed');
            if (checkSpan) checkSpan.innerHTML = '✅';
        } else {
            if (checkSpan) checkSpan.innerHTML = '⬜';
        }
    });
}

window.restartQuiz = restartQuiz;
window.closeNotificationModal = closeNotificationModal;