<style>
    @keyframes shimmering {
      0% {
        background-position: -1200px 0;
      }
      100% {
        background-position: 1200px 0;
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
      height: 180px; /* You can adjust the height as needed */
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
      width: 20px; /* Adjust this width as per your needs */
    }
  </style>
</head>
<body>
  <div class="container mt-2 p-0 mx-0">
    <div class="row g-1">
      <div class="col-md-12">
        <div class="shimmer-box shimmer">
          <div class="sh-img"></div>
        </div>
      </div>
    </div>
  </div>