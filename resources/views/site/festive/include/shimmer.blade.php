<style>
    @keyframes shimmering {
      0% {
        background-position: -1000px 0;
      }
      100% {
        background-position: 1000px 0;
      }
    }

    .shimmer {
      animation: shimmering 3s linear infinite;
      background: linear-gradient(90deg, rgba(226, 226, 226, 1) 8%, rgba(238, 238, 238, 1) 18%, rgba(226, 226, 226, 1) 33%);
      background-size: 1300px 100%;
      border-radius: 5px;
      margin-bottom: 10px;
    }

    /* Box dimensions */
    .sh-img {
      width: 100%;
      height: 300px; /* You can adjust the height as needed */
    }
    @keyframes shimmering-text {
      0% {
        background-position: -300px 0;
      }
      100% {
        background-position: 300px 0;
      }
    }

    .shimmer-text {
      display: inline-block;
      animation: shimmering-text 2s linear infinite;
      background: linear-gradient(90deg, rgba(226, 226, 226, 1) 8%, rgba(238, 238, 238, 1) 18%, rgba(226, 226, 226, 1) 33%);
      background-size: 300px 100%;
      color: transparent;
      border-radius: 4px;
    }

    .shimmer-wrapper {
      display: inline-block;
      width: 150px; /* Adjust this width as per your needs */
    }
  </style>
</head>
<body>

  <div class="container mx-0 p-0">
    <div class="fs-15 mb-3 text-muted">
        <div class="shimmer-wrapper">
          <strong class="shimmer-text">{{ $contents->count() }}</strong> content found
        </div>
      </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="shimmer-box shimmer">
          <div class="sh-img"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="shimmer-box shimmer">
          <div class="sh-img"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="shimmer-box shimmer">
          <div class="sh-img"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="shimmer-box shimmer">
          <div class="sh-img"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="shimmer-box shimmer">
          <div class="sh-img"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="shimmer-box shimmer">
          <div class="sh-img"></div>
        </div>
      </div>
    </div>
  </div>