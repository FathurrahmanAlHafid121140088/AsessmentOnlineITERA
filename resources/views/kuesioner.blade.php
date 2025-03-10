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
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
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
    <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8 col-lg-7 quiz" >
              <div class="quiz-container">
                  <h2 class="quiz-title">
                      <i class="fas fa-brain me-2"></i>Quiz Mental Health MH-38
                  </h2>
                  
                  <div class="progress-container">
                      <div class="progress">
                          <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <div class="text-end mt-1">
                          <small id="progress-text">0/38 Pertanyaan Dijawab</small>
                      </div>
                  </div>
                  
                  <form id="quizForm">
                    @php
                      $questions = [
                      ["number" => 1, "text" => "Seberapa bahagia, puas, atau senangkah Anda dengan kehidupan pribadi Anda selama sebulan terakhir?", "option" => "a"],
                      ["number" => 2, "text" => "Seberapa sering Anda merasa kesepian selama sebulan terakhir?", "option" => "c"],
                      ["number" => 3, "text" => "Seberapa sering Anda merasa gugup atau gelisah?", "option" => "d"],
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
                    <p class="question-text">
                        <span class="question-number">{{ $question['number'] }}</span> {{ $question['text'] }}
                    </p>
                    
                    @if ($question['number'] == 1)
                        <div class="row">
                            <div class="col-md-6">
                                <label class="custom-radio" for="q2a">
                                    <div class="option-container">
                                        <input class="form-check-input" type="radio" name="question1" id="q2a" value="6" required>
                                        <span class="form-check-label">Sangat senang, tidak bisa lebih puas atau senang lagi</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="custom-radio" for="q2b">
                                    <div class="option-container">
                                        <input class="form-check-input" type="radio" name="question1" id="q2b" value="5">
                                        <span class="form-check-label">Sangat senang hampir sepanjang waktu</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="custom-radio" for="q2c">
                                    <div class="option-container">
                                        <input class="form-check-input" type="radio" name="question1" id="q2c" value="4">
                                        <span class="form-check-label">Secara umum, puas, senang</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="custom-radio" for="q2d">
                                    <div class="option-container">
                                        <input class="form-check-input" type="radio" name="question1" id="q2d" value="3">
                                        <span class="form-check-label">Terkadang cukup puas, terkadang tidak puas</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="custom-radio" for="q2e">
                                    <div class="option-container">
                                        <input class="form-check-input" type="radio" name="question1" id="q2e" value="2">
                                        <span class="form-check-label">Secara umum tidak puas, tidak bahagia</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="custom-radio" for="q2f">
                                    <div class="option-container">
                                        <input class="form-check-input" type="radio" name="question2" id="q2f" value="1">
                                        <span class="form-check-label">Sangat tidak puas, sering tidak bahagia</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @elseif (in_array($question['number'], [2, 3, 4, 5, 6, 7, 10, 11, 13, 17, 18, 19, 22, 23, 26, 29, 30, 31, 34, 36]))
                        <div class="row">
                            <div class="col-md-6">
                                <label class="custom-radio" for="q{{ $question['number'] }}a">
                                    <div class="option-container">
                                        <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}a" value="6" required>
                                        <span class="form-check-label">Sepanjang Waktu</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="custom-radio" for="q{{ $question['number'] }}b">
                                    <div class="option-container">
                                        <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}b" value="5">
                                        <span class="form-check-label">Hampir Sepanjang Waktu</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="custom-radio" for="q{{ $question['number'] }}c">
                                    <div class="option-container">
                                        <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}c" value="4">
                                        <span class="form-check-label">Cukup Sering</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="custom-radio" for="q{{ $question['number'] }}d">
                                    <div class="option-container">
                                        <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}d" value="3">
                                        <span class="form-check-label">Kadang-kadang</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="custom-radio" for="q{{ $question['number'] }}e">
                                    <div class="option-container">
                                        <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}e" value="2">
                                        <span class="form-check-label">Jarang</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="custom-radio" for="q{{ $question['number'] }}f">
                                    <div class="option-container">
                                        <input class="form-check-input" type="radio" name="question{{ $question['number'] }}" id="q{{ $question['number'] }}f" value="1">
                                        <span class="form-check-label">Tidak Pernah</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @elseif (in_array($question['number'], [3, 12, 15, 16, 20, 21, 24, 27, 32, 35]))
                    <div class="row">
                      <div class="col-md-6">
                          <label class="custom-radio" for="q2a">
                              <div class="option-container">
                                  <input class="form-check-input" type="radio" name="question2" id="q2a" value="1" required>
                                  <span class="form-check-label">Selalu</span>
                              </div>
                          </label>
                      </div>
                      <div class="col-md-6">
                          <label class="custom-radio" for="q2b">
                              <div class="option-container">
                                  <input class="form-check-input" type="radio" name="question2" id="q2b" value="2">
                                  <span class="form-check-label">Sangat Sering</span>
                              </div>
                          </label>
                      </div>
                      <div class="col-md-6">
                          <label class="custom-radio" for="q2c">
                              <div class="option-container">
                                  <input class="form-check-input" type="radio" name="question2" id="q2c" value="3">
                                  <span class="form-check-label">Cukup Sering</span>
                              </div>
                          </label>
                      </div>
                      <div class="col-md-6">
                          <label class="custom-radio" for="q2d">
                              <div class="option-container">
                                  <input class="form-check-input" type="radio" name="question2" id="q2d" value="4">
                                  <span class="form-check-label">Kadang-Kadang</span>
                              </div>
                          </label>
                      </div>
                      <div class="col-md-6">
                          <label class="custom-radio" for="q2e">
                              <div class="option-container">
                                  <input class="form-check-input" type="radio" name="question2" id="q2e" value="5">
                                  <span class="form-check-label">Hampir Tidak Pernah</span>
                              </div>
                          </label>
                      </div>
                      <div class="col-md-6">
                          <label class="custom-radio" for="q2f">
                              <div class="option-container">
                                  <input class="form-check-input" type="radio" name="question2" id="q2f" value="6">
                                  <span class="form-check-label">Tidak Pernah</span>
                              </div>
                          </label>
                      </div>
                  </div>
                  @elseif ($question['number'] == 8)
                    <div class="row">
                        <div class="col-md-6">
                            <label class="custom-radio" for="q8a">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question8" id="q8a" value="6" required>
                                    <span class="form-check-label">Tidak, sama sekali tidak.</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-radio" for="q8b">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question8" id="q8b" value="5">
                                    <span class="form-check-label">Mungkin sedikit</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-radio" for="q8c">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question8" id="q8c" value="4">
                                    <span class="form-check-label">Ya, tetapi tidak cukup untuk dikhawatirkan.</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-radio" for="q8d">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question8" id="q8d" value="3">
                                    <span class="form-check-label">Ya, dan saya sedikit khawatir.</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-radio" for="q8e">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question8" id="q8e" value="2">
                                    <span class="form-check-label">Ya, dan saya cukup khawatir.</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-radio" for="q8f">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question8" id="q8f" value="1">
                                    <span class="form-check-label">Ya, saya sangat khawatir tentang hal itu.</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    @elseif ($question['number'] == 9)
                    <div class="row">
                        <div class="col-md-6">
                            <label class="custom-radio" for="q9a">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question9" id="q9a" value="1" required>
                                    <span class="form-check-label">Ya, sampai pada titik di mana saya tidak peduli terhadap apa pun selama berhari-hari.</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-radio" for="q9b">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question9" id="q9b" value="2">
                                    <span class="form-check-label">Ya, sangat tertekan hampir setiap hari.</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-radio" for="q9c">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question9" id="q9c" value="3">
                                    <span class="form-check-label">Ya, cukup tertekan beberapa kali.</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-radio" for="q9d">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question9" id="q9d" value="4">
                                    <span class="form-check-label">Ya, sedikit tertekan sesekali.</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-radio" for="q9e">
                                <div class="option-container">
                                    <input class="form-check-input" type="radio" name="question9" id="q9e" value="5">
                                    <span class="form-check-label">Tidak, tidak pernah merasa tertekan sama sekali.</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    @elseif ($question['number'] == 14)
                    <div class="row">
                      <div class="col-md-6">
                          <label class="custom-radio" for="q2a">
                              <div class="option-container">
                                  <input class="form-check-input" type="radio" name="question2" id="q2a" value="6" required>
                                  <span class="form-check-label">Ya, sangat pasti</span>
                              </div>
                          </label>
                      </div>
                      <div class="col-md-6">
                          <label class="custom-radio" for="q2b">
                              <div class="option-container">
                                  <input class="form-check-input" type="radio" name="question2" id="q2b" value="5">
                                  <span class="form-check-label">Ya, untuk sebagian besar.</span>
                              </div>
                          </label>
                      </div>
                      <div class="col-md-6">
                          <label class="custom-radio" for="q2c">
                              <div class="option-container">
                                  <input class="form-check-input" type="radio" name="question2" id="q2c" value="4">
                                  <span class="form-check-label">Ya, saya rasa begitu.</span>
                              </div>
                          </label>
                      </div>
                      <div class="col-md-6">
                          <label class="custom-radio" for="q2d">
                              <div class="option-container">
                                  <input class="form-check-input" type="radio" name="question2" id="q2d" value="3">
                                  <span class="form-check-label">Tidak, tidak terlalu baik</span>
                              </div>
                          </label>
                      </div>
                      <div class="col-md-6">
                          <label class="custom-radio" for="q2e">
                              <div class="option-container">
                                  <input class="form-check-input" type="radio" name="question2" id="q2e" value="2">
                                  <span class="form-check-label">Tidak, dan saya agak terganggu</span>
                              </div>
                          </label>
                      </div>
                      <div class="col-md-6">
                          <label class="custom-radio" for="q2f">
                              <div class="option-container">
                                  <input class="form-check-input" type="radio" name="question2" id="q2f" value="1">
                                  <span class="form-check-label"> Tidak, dan sangat terganggu</span>
                              </div>
                          </label>
                      </div>
                  </div>
                  @elseif ($question['number'] == 25)
                  <div class="row">
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2a">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2a" value="a" required>
                                <span class="form-check-label">Sangat terganggu.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2b">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2b" value="b">
                                <span class="form-check-label">Lumayan terganggu.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2c">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2c" value="c">
                                <span class="form-check-label">Terganggu.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2d">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2d" value="d">
                                <span class="form-check-label">Agak terganggu.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2e">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2e" value="e">
                                <span class="form-check-label">Sedikit terganggu.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2f">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2f" value="f">
                                <span class="form-check-label">Tidak terganggu sama sekali.</span>
                            </div>
                        </label>
                    </div>
                </div>
                  @elseif ($question['number'] == 28)
                  <div class="row">
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2a">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2a" value="1" required>
                                <span class="form-check-label">Sangat Sering.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2b">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2b" value="2">
                                <span class="form-check-label">Cukup Sering.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2c">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2c" value="3">
                                <span class="form-check-label">Beberapa Kali.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2d">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2d" value="4">
                                <span class="form-check-label">Satu Kali.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2e">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2e" value="5">
                                <span class="form-check-label">Tidak Pernah.</span>
                            </div>
                        </label>
                    </div>
                </div>
                  @elseif ($question['number'] == 33)
                  <div class="row">
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2a">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2a" value="1" required>
                                <span class="form-check-label">Ya, sangat ekstrem hingga merasa sakit atau hampir sakit.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2b">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2b" value="2">
                                <span class="form-check-label">Ya, sangat terganggu.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2c">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2c" value="3">
                                <span class="form-check-label">Ya, cukup terganggu.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2d">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2d" value="4">
                                <span class="form-check-label">Ya, agak terganggu, cukup mengganggu saya.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2e">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2e" value="5">
                                <span class="form-check-label">Ya, sedikit terganggu.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2f">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2f" value="6">
                                <span class="form-check-label">Tidak, sama sekali tidak.</span>
                            </div>
                        </label>
                    </div>
                </div>
                  @elseif ($question['number'] == 37)
                  <div class="row">
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2a">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2a" value="6" required>
                                <span class="form-check-label">Selalu, setiap hari.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2b">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2b" value="5">
                                <span class="form-check-label">Hampir setiap hari.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2c">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2c" value="4">
                                <span class="form-check-label">Sebagian besar hari.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2d">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2d" value="3">
                                <span class="form-check-label">Beberapa hari, tetapi biasanya tidak.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2e">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2e" value="2">
                                <span class="form-check-label">Hampir tidak pernah.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2f">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2f" value="1">
                                <span class="form-check-label">Tidak pernah bangun dengan perasaan segar.</span>
                            </div>
                        </label>
                    </div>
                </div>
                  @elseif ($question['number'] == 38)
                  <div class="row">
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2a">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2a" value="1" required>
                                <span class="form-check-label">Ya, hampir lebih dari yang bisa saya tahan.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2b">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2b" value="2">
                                <span class="form-check-label">Ya, cukup banyak tekanan.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2c">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2c" value="3">
                                <span class="form-check-label">Ya, sedikit lebih dari biasanya.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2d">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2d" value="4">
                                <span class="form-check-label">Ya, sedikit, tetapi masih dalam batas normal.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2e">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2e" value="5">
                                <span class="form-check-label">Ya, sedikit saja.</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-radio" for="q2f">
                            <div class="option-container">
                                <input class="form-check-input" type="radio" name="question2" id="q2f" value="6">
                                <span class="form-check-label">Tidak, sama sekali tidak.</span>
                            </div>
                        </label>
                    </div>
                </div>
                    @else
                        <x-option-{{ $question['option'] }}></x-option-{{ $question['option'] }}>
                    @endif
                </div>
            @endforeach
                      <div id="results-container" class="feedback"></div>
                      
                      <!-- Tombol Submit -->
                      <div class="d-grid gap-2 mt-4">
                          <button type="submit" class="btn btn-primary btn-submit" id="submit-button">
                              <i class="fas fa-paper-plane me-2"></i>Kirim Jawaban
                          </button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
  <x-footer></x-footer>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="{{ asset('js/script-quiz.js') }}"></script>
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<!-- * *                               SB Forms JS                               * *-->
<!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</html>