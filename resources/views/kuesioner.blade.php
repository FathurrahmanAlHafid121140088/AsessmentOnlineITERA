<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ $title }}</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
          <!-- Vendor CSS Files -->
        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
        <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/main.css') }}" rel="stylesheet">
        <link href="{{ asset('css/stylekuesioner.css') }}" rel="stylesheet">
    </head>
</head>
<x-navbar></x-navbar>
<body>
<!-- Tombol untuk memunculkan kembali sidebar -->
<div id="show-sidebar" class="show-button">
    <div class="arrow-top"></div>
    <div class="arrow-bottom"></div>
</div>

<!-- Sidebar Soal -->
<div id="question-status-container" class="status-container">
    <div class="sidebar-title">Daftar Soal</div>
    <div id="question-grid" class="grid-container">
        <!-- Soal akan dimasukkan ke sini via JS -->
    </div>

    <div id="toggle-sidebar" class="toggle-button">
        <div class="arrow-top"></div>
        <div class="arrow-bottom"></div>
    </div>
</div>

    <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8 col-lg-7 quiz" >
              <div class="quiz-container">
                  <h2 class="quiz-title">
                      <i class="fas fa-brain me-2"></i>Quiz Mental Health MHI-38
                  </h2>
                  <form id="quizForm">
                    @php
                      $questions = [
                      ["number" => 1, "text" => "Seberapa bahagia, puas, atau senangkah Anda dengan kehidupan pribadi Anda selama sebulan terakhir?", "option" => "a"],
                      ["number" => 2, "text" => "Seberapa sering Anda merasa kesepian selama sebulan terakhir?", "option" => "c"],
                      ["number" => 3, "text" => "Seberapa sering Anda merasa gugup atau gelisah ketika dihadapkan pada situasi yang menyenangkan atau tak terduga selama sebulan terakhir?", "option" => "d"],
                      ["number" => 4, "text" => "Selama sebulan terakhir, seberapa sering Anda merasa bahwa masa depan terlihat penuh harapan dan menjanjikan?", "option" => "c"],
                      ["number" => 5, "text" => "Berapa banyak waktu, selama sebulan terakhir, kehidupan sehari-hari Anda penuh dengan hal-hal yang menarik bagi Anda?", "option" => "c"],
                      ["number" => 6, "text" => "Seberapa sering, selama sebulan terakhir, Anda merasa rileks dan bebas dari ketegangan?", "option" => "c"],
                      ["number" => 7, "text" => "Selama sebulan terakhir, berapa banyak waktu yang Anda habiskan untuk menikmati hal-hal yang Anda lakukan?", "option" => "c"],
                      ["number" => 8, "text" => "Selama sebulan terakhir, pernahkah Anda merasa kehilangan akal sehat, atau kehilangan kendali atas cara Anda bertindak, berbicara, berpikir, merasakan, atau ingatan Anda?", "option" => "e"],
                      ["number" => 9, "text" => "Apakah Anda merasa tertekan selama sebulan terakhir?", "option" => "f"],
                      ["number" => 10, "text" => "Selama sebulan terakhir, berapa banyak waktu yang Anda gunakan untuk merasa dicintai dan diinginkan?", "option" => "c"],
                      ["number" => 11, "text" => "Seberapa sering, selama sebulan terakhir, Anda menjadi orang yang sangat gugup?", "option" => "c"],
                      ["number" => 12, "text" => "Ketika Anda bangun di pagi hari, dalam sebulan terakhir ini, kira-kira seberapa sering Anda berharap untuk mendapatkan hari yang menarik?", "option" => "d"],
                      ["number" => 13, "text" => "Seberapa sering Anda merasa tegang atau sangat gelisah?", "option" => "c"],
                      ["number" => 14, "text" => "Selama sebulan terakhir, apakah Anda memegang kendali penuh atas perilaku, pikiran, emosi, atau perasaan Anda?", "option" => "g"],
                      ["number" => 15, "text" => "Selama sebulan terakhir, seberapa sering tangan Anda bergetar ketika mencoba melakukan sesuatu?", "option" => "d"],
                      ["number" => 16, "text" => "Selama sebulan terakhir, seberapa sering Anda merasa tidak memiliki sesuatu yang dinantikan?", "option" => "d"],
                      ["number" => 17, "text" => "Seberapa sering, selama sebulan terakhir, Anda merasa tenang dan damai?", "option" => "c"],
                      ["number" => 18, "text" => "Seberapa sering, selama sebulan terakhir, Anda merasa stabil secara emosional?", "option" => "c"],
                      ["number" => 19, "text" => "Seberapa sering, selama sebulan terakhir, Anda merasa murung?", "option" => "c"],
                      ["number" => 20, "text" => "Seberapa sering Anda merasa ingin menangis, selama sebulan terakhir?", "option" => "d"],
                      ["number" => 21, "text" => "Selama sebulan terakhir, seberapa sering Anda merasa bahwa orang lain akan lebih baik jika Anda mati?", "option" => "d"],
                      ["number" => 22, "text" => "Berapa banyak waktu, selama sebulan terakhir, Anda dapat bersantai tanpa kesulitan?", "option" => "c"],
                      ["number" => 23, "text" => "Seberapa sering, selama sebulan terakhir, Anda merasa bahwa hubungan cinta Anda, mencintai dan dicintai, terasa utuh dan lengkap?", "option" => "c"],
                      ["number" => 24, "text" => "Seberapa sering, selama sebulan terakhir, Anda merasa bahwa tidak ada yang berjalan sesuai dengan yang Anda inginkan?", "option" => "d"],
                      ["number" => 25, "text" => "Seberapa sering Anda merasa terganggu oleh rasa gugup, atau \"kegelisahan\" Anda, selama sebulan terakhir?", "option" => "c"],
                      ["number" => 26, "text" => "Selama sebulan terakhir, berapa banyak waktu yang Anda gunakan untuk menjalani petualangan yang luar biasa bagi Anda?", "option" => "c"],
                      ["number" => 27, "text" => "Seberapa sering, selama sebulan terakhir, Anda merasa sangat terpuruk sehingga tidak ada yang dapat menghibur Anda?", "option" => "d"],
                      ["number" => 28, "text" => "Selama sebulan terakhir, apakah Anda berpikir untuk bunuh diri?", "option" => "i"],
                      ["number" => 29, "text" => "Selama sebulan terakhir, berapa kali Anda merasa gelisah, resah, atau tidak sabar?", "option" => "c"],
                      ["number" => 30, "text" => "Selama sebulan terakhir, berapa banyak waktu yang Anda habiskan untuk murung atau merenung tentang berbagai hal?", "option" => "c"],
                      ["number" => 31, "text" => "Seberapa sering, selama sebulan terakhir, Anda merasa ceria dan gembira?", "option" => "c"],
                      ["number" => 32, "text" => "Selama sebulan terakhir, seberapa sering Anda merasa gelisah, kesal, atau bingung?", "option" => "d"],
                      ["number" => 33, "text" => "Selama sebulan terakhir, apakah Anda pernah merasa cemas atau khawatir?", "option" => "j"],
                      ["number" => 34, "text" => "Selama sebulan terakhir, berapa banyak waktu yang Anda habiskan untuk menjadi orang yang bahagia?", "option" => "c"],
                      ["number" => 35, "text" => "Seberapa sering selama sebulan terakhir Anda merasa perlu menenangkan diri?", "option" => "d"],
                      ["number" => 36, "text" => "Selama sebulan terakhir, seberapa sering Anda merasa sedih atau sangat terpuruk?", "option" => "c"],
                      ["number" => 37, "text" => "Seberapa sering, selama sebulan terakhir, Anda bangun tidur dengan perasaan segar dan beristirahat?", "option" => "k"],
                      ["number" => 38, "text" => "Selama sebulan terakhir, apakah Anda pernah mengalami atau merasa berada di bawah tekanan, stres, atau tekanan?", "option" => "l"],
                  ];
                @endphp
                
                @foreach ($questions as $question)
                <div class="question" id="question{{ $question['number'] }}">
                    <div class="question-text">
                        <div class="question-number">{{ $question['number'] }}</div>
                        <p>{{ $question['text'] }}</p>
                    </div>
                    
                    @if ($question['number'] == 1)
                    <div class="row-soal">
                        @foreach ([
                            6 => 'Sangat senang, tidak bisa lebih puas lagi',
                            5 => 'Sangat senang hampir sepanjang waktu',
                            4 => 'Secara umum, puas, senang',
                            3 => 'Terkadang cukup puas, terkadang tidak puas',
                            2 => 'Secara umum tidak puas, tidak bahagia',
                            1 => 'Sangat tidak puas, sering tidak bahagia'
                        ] as $value => $label)
                            <div class="col-md-6">
                                <label class="custom-radio" for="q1{{ $value }}">
                                    <div class="option-container">
                                        <input class="form-check-input" type="radio" name="question1" id="q1{{ $value }}" value="{{ $value }}" required>
                                        <span class="form-check-label">{{ $label }}</span>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @elseif (in_array($question['number'], [2, 4, 5, 6, 7, 10, 11, 13, 17, 18, 19, 22, 23, 26, 29, 30, 31, 34, 36]))
                    <div class="row-soal">
                      @foreach ([
                          6 => 'Sepanjang Waktu',
                          5 => 'Hampir Sepanjang Waktu',
                          4 => 'Cukup Sering',
                          3 => 'Kadang-kadang',
                          2 => 'Jarang',
                          1 => 'Tidak Pernah'
                      ] as $value => $label)
                          <div class="col-md-6">
                              <label class="custom-radio" for="q{{ $question['number'] }}{{ $value }}">
                                  <div class="option-container">
                                      <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}{{ $value }}" value="{{ $value }}" required>
                                      <span class="form-check-label">{{ $label }}</span>
                                  </div>
                              </label>
                          </div>
                      @endforeach
                  </div>
                    @elseif (in_array($question['number'], [3, 12, 15, 16, 20, 21, 24, 27, 32, 35]))
                    <div class="row-soal">
                      @foreach ([
                          1 => 'Selalu',
                          2 => 'Sangat Sering',
                          3 => 'Cukup Sering',
                          4 => 'Kadang-kadang',
                          5 => 'Hampir Tidak Pernah',
                          6 => 'Tidak Pernah'
                      ] as $value => $label)
                          <div class="col-md-6">
                              <label class="custom-radio" for="q{{ $question['number'] }}{{ $value }}">
                                  <div class="option-container">
                                      <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}{{ $value }}" value="{{ $value }}" required>
                                      <span class="form-check-label">{{ $label }}</span>
                                  </div>
                              </label>
                          </div>
                      @endforeach
                  </div>
                  @elseif ($question['number'] == 8)
                  <div class="row-soal">
                    @foreach ([
                        6 => 'Tidak, sama sekali tidak.',
                        5 => 'Mungkin sedikit.',
                        4 => 'Ya, tetapi tidak terlalu dikhawatirkan.',
                        3 => 'Ya, dan saya sedikit khawatir.',
                        2 => 'Ya, dan saya cukup khawatir.',
                        1 => 'Ya, saya sangat khawatir tentang itu.'
                    ] as $value => $label)
                        <div class="col-md-6">
                            <label class="custom-radio" for="q{{ $question['number'] }}{{ $value }}">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}{{ $value }}" value="{{ $value }}" required>
                                    <span class="form-check-label">{{ $label }}</span>
                                </div>
                            </label>
                        </div>
                    @endforeach
                  </div>
                    @elseif ($question['number'] == 9)
                    <div class="row-soal">
                      @foreach ([
                          1 => 'Ya, tertekan setiap hari',
                          2 => 'Ya, sangat tertekan hampir setiap hari.',
                          3 => 'Ya, cukup tertekan beberapa kali.',
                          4 => 'Ya, sedikit tertekan sesekali.',
                          5 => 'Tidak, tidak pernah sama sekali.',
                      ] as $value => $label)
                          <div class="col-md-6">
                              <label class="custom-radio" for="q{{ $question['number'] }}{{ $value }}">
                                  <div class="option-container">
                                      <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}{{ $value }}" value="{{ $value }}" required>
                                      <span class="form-check-label">{{ $label }}</span>
                                  </div>
                              </label>
                          </div>
                      @endforeach
                    </div>
                    @elseif ($question['number'] == 14)
                    <div class="row-soal">
                      @foreach ([
                          6 => 'Ya, sangat yakin.',
                          5 => 'Ya, untuk sebagian besar.',
                          4 => 'Ya, saya rasa begitu.',
                          3 => 'Tidak, kurang baik.',
                          2 => 'Tidak, dan saya agak terganggu.',
                          1 => 'Tidak, dan saya sangat terganggu.'
                      ] as $value => $label)
                          <div class="col-md-6">
                              <label class="custom-radio" for="q{{ $question['number'] }}{{ $value }}">
                                  <div class="option-container">
                                      <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}{{ $value }}" value="{{ $value }}" required>
                                      <span class="form-check-label">{{ $label }}</span>
                                  </div>
                              </label>
                          </div>
                      @endforeach
                    </div>
                  @elseif ($question['number'] == 25)
                  <div class="row-soal">
                    @foreach ([
                        1 => 'Benar-benar terganggu.',
                        2 => 'Sangat terganggu.',
                        3 => 'Cukup terganggu oleh rasa gugup.',
                        4 => 'Agak terganggu, cukup untuk menyadarinya.',
                        5 => 'Sedikit terganggu oleh rasa gugup.',
                        6 => 'Tidak terganggu sama sekali oleh hal ini'
                    ] as $value => $label)
                        <div class="col-md-6">
                            <label class="custom-radio" for="q{{ $question['number'] }}{{ $value }}">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}{{ $value }}" value="{{ $value }}" required>
                                    <span class="form-check-label">{{ $label }}</span>
                                </div>
                            </label>
                        </div>
                    @endforeach
                  </div>
                  @elseif ($question['number'] == 28)
                  <div class="row-soal">
                    @foreach ([
                        1 => 'Ya, sangat sering.',
                        2 => 'Ya, cukup sering.',
                        3 => 'Ya, beberapa kali.',
                        4 => 'Ya, satu kali.',
                        5 => 'Tidak, tidak pernah.',
                    ] as $value => $label)
                        <div class="col-md-6">
                            <label class="custom-radio" for="q{{ $question['number'] }}{{ $value }}">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}{{ $value }}" value="{{ $value }}" required>
                                    <span class="form-check-label">{{ $label }}</span>
                                </div>
                            </label>
                        </div>
                    @endforeach
                  </div>
                  @elseif ($question['number'] == 33)
                  <div class="row-soal">
                    @foreach ([
                        1 => 'Ya, sangat ekstrem hingga merasa sakit atau hampir sakit.',
                        2 => 'Ya, sangat terganggu.',
                        3 => 'Ya, cukup terganggu.',
                        4 => 'Ya, agak terganggu, cukup mengganggu saya.',
                        5 => 'Ya, tidak terlalu mengganggu.',
                        6 => 'Tidak, sama sekali tidak.',
                    ] as $value => $label)
                        <div class="col-md-6">
                            <label class="custom-radio" for="q{{ $question['number'] }}{{ $value }}">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}{{ $value }}" value="{{ $value }}" required>
                                    <span class="form-check-label">{{ $label }}</span>
                                </div>
                            </label>
                        </div>
                    @endforeach
                  </div>
                  @elseif ($question['number'] == 37)
                  <div class="row-soal">
                    @foreach ([
                        6 => 'Selalu, setiap hari.',
                        5 => 'Hampir setiap hari.',
                        4 => 'Sebagian besar hari.',
                        3 => 'Beberapa hari, tetapi biasanya tidak.',
                        2 => 'Hampir tidak pernah.',
                        1 => 'Tidak pernah bangun dengan perasaan segar.'
                    ] as $value => $label)
                        <div class="col-md-6">
                            <label class="custom-radio" for="q{{ $question['number'] }}{{ $value }}">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}{{ $value }}" value="{{ $value }}" required>
                                    <span class="form-check-label">{{ $label }}</span>
                                </div>
                            </label>
                        </div>
                    @endforeach
                  </div>
                  @elseif ($question['number'] == 38)
                  <div class="row-soal">
                    @foreach ([
                        1 => 'Ya, hampir lebih dari yang bisa saya tahan.',
                        2 => 'Ya, cukup banyak tekanan.',
                        3 => 'Ya, sedikit lebih dari biasanya.',
                        4 => 'Ya, namun masih dalam batas normal.',
                        5 => 'Ya, sedikit saja.',
                        6 => 'Tidak, sama sekali tidak.',
                    ] as $value => $label)
                        <div class="col-md-6">
                            <label class="custom-radio" for="q{{ $question['number'] }}{{ $value }}">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}{{ $value }}" value="{{ $value }}" required>
                                    <span class="form-check-label">{{ $label }}</span>
                                </div>
                            </label>
                        </div>
                    @endforeach
                  </div>
                    @endif
                </div>
            @endforeach
                      <div id="results-container" class="feedback"></div>
                            <div id="question-indicator" class="question-indicator mt-2" data-total-questions="{{ count($questions) }}">
                                <h5 class="indicator-title text-center">Daftar Soal</h5>
                                @for ($i = 1; $i <= count($questions); $i += 10)
                                    <div class="indicator-group mb-2">
                                        @for ($j = $i; $j < $i + 10 && $j <= count($questions); $j++)
                                            <span class="question-box" data-question="{{ $j }}">
                                                <span class="number">{{ $j }}</span>
                                                <span class="status">❌</span>
                                            </span>
                                        @endfor
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                        <button type="button" class="btn btn-primary btn-submit" id="submit-button">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Jawaban
                        </button>
                    </div> 
                    </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
  <!-- Tombol Arrow Up -->
  <button id="scrollToTopBtn" class="scroll-to-top">
    <i class="fas fa-arrow-up"></i>
</button>
<button id="scrollToBottomBtn" class="scroll-to-bottom">
  <i class="fas fa-arrow-down"></i>
</button>

  <x-footer></x-footer>
</body>
<!-- Link CDN SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="{{ asset('js/script-quiz.js') }}"></script>
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<!-- * *                               SB Forms JS                               * *-->
<!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</html>