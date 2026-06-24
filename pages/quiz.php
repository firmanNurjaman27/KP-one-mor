<div id="page-quiz" class="page">
    <div class="section-header">
        <h2>📝 Ujian Kemampuan Kuis Halal-Haram</h2>
        <p>Uji sejauh mana pemahamanmu mengenai aturan makanan, minuman, dan transaksi syariah.</p>
    </div>
    <div class="quiz-wrapper">
        <div id="quizIntro">
            <p style="margin-bottom: 15px;">Kuis terdiri dari pertanyaan pilihan ganda. Skor tertinggi Anda saat ini: <strong><?php echo $bestQuizScore; ?></strong></p>
            <button class="cta-glow" onclick="startQuizEngine()">Mulai Kuis Sekarang</button>
        </div>
        
        <div id="quizPlayArea" style="display:none;">
            <div class="quiz-header">
                <div class="quiz-progress-text">Pertanyaan <span id="currentQIndex">1</span> dari <span id="totalQCount">0</span></div>
                <div class="quiz-progress-bar"><div class="quiz-progress-fill" id="quizBarFill" style="width: 0%"></div></div>
            </div>
            <div class="quiz-card">
                <div class="quiz-question" id="quizQuestionText">Memuat pertanyaan...</div>
                <div class="quiz-options" id="quizOptionsContainer">
                    <!-- Dynamic Options -->
                </div>
            </div>
            <button class="quiz-next" id="btnNextQuiz" style="display:none;" onclick="nextQuestion()">Pertanyaan Selanjutnya <i class="fas fa-chevron-right"></i></button>
        </div>

        <div id="quizResultArea" style="display:none;" class="quiz-result">
            <h3>Kuis Selesai!</h3>
            <div class="score-big" id="finalScoreDisplay">0</div>
            <p id="quizResultFeedback">Kerja bagus!</p>
            <button class="cta-glow" onclick="location.reload()">Selesai & Simpan Skor</button>
        </div>
    </div>
</div>