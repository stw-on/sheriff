<html lang="de">
    <head>
        <title>Check-In</title>
        <style>
            * {
                box-sizing: border-box;
            }

            html, body {
                font-family: 'Fira Sans', sans-serif;
                margin: 0;
                padding: 0;
                position: relative;
            }

            @page {
                size: A4 landscape;
            }

            .page-container .page {
                overflow: hidden;
                padding: 16mm;
                height: 100%;
            }

            .page-container .page .page-inner {
                display: grid;
                grid-template-columns: 130mm 1fr;
                grid-gap: 12mm;
                height: 100%;
            }

            .page-container .page .page-inner .left-column {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .page-container .page .page-inner .left-column .qr-code {
                position: relative;
                height: 130mm;
                width: 130mm;
            }

            .page-container .page .page-inner .left-column .qr-code > svg {
                display: block;
                height: 100%;
                width: auto;
            }

            .page-container .page .page-inner .left-column .logo {
                width: 66%;
            }

            .page-container .page .page-inner .instructions {
                display: grid;
                grid-template-columns: auto 1fr;
                grid-gap: 5mm;
                align-items: start;
                align-self: start;
                font-size: 9mm;
            }

            .page-container .page .page-inner .instructions .number {
                display: inline;
                font-size: 15mm;
                text-align: center;
                line-height: 20mm;
                font-weight: bold;
                background: #e30712;
                color: white;
                border-radius: 50%;
                width: 20mm;
                height: 20mm;
            }

            .page-container .page .page-inner .instructions .instruction {
                padding-top: 4mm;
                line-height: 1.3;
            }

            .page-container .page .page-inner .instructions .instruction .translation {
                padding-top: 2mm;
                font-size: 6mm;
                color: #7f7f7f;
            }

            .spacer {
                flex-grow: 1;
            }
        </style>
        <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;700&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/qrcode-svg@1.1.0/lib/qrcode.js" integrity="sha256-B0r4v1ZsVPtonbmMa3TbF7s1N0mu8fR+FxQTeDm3rJY=" crossorigin="anonymous"></script>
        <script>
            const SHERIFF_QR_URL = '{{ $qr_url }}';
        </script>
    </head>
    <body>
        <div class="page-container">
            <div class="page">
                <div class="page-inner">
                    <div class="left-column">
                        <div class="qr-code" id="qr-code"><!-- QR code will be inserted here --></div>
                        <div class="spacer"></div>
                        <svg
                            class="logo"
                            viewBox="0 0 398.868 71.568"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                        >
                            <g transform="translate(-1.963 -.261)">
                                <defs>
                                    <path id="a" d="M401 .261H1.963v72.534H401z" />
                                </defs>
                                <clipPath id="b">
                                    <use height="100%" overflow="visible" width="100%" xlink:href="#a" />
                                </clipPath>
                                <path
                                    clip-path="url(#b)"
                                    d="M290.182 54.435l1.945-.495c.288.637.504 1.272.504 1.91 1.225-1.273 2.519-1.91 3.887-1.91.646 0 1.295.143 1.799.495.575.354 1.007.707 1.223 1.202.218.565.289 1.06.289 1.556v9.331h-1.943v-8.342c0-.637-.072-1.061-.072-1.343a2.828 2.828 0 00-.432-.778c-.287-.283-.719-.424-1.367-.424-.504 0-1.079.141-1.729.495-.648.353-1.223.707-1.654 1.201v9.19h-1.944v-9.19c0-.777-.071-1.414-.071-1.696a8.698 8.698 0 00-.435-1.202zM284.927 63.978l.793 1.274c-1.151.989-2.59 1.554-4.246 1.554-1.727 0-3.094-.564-4.103-1.768-.937-1.131-1.438-2.756-1.438-4.807 0-1.061.071-1.908.288-2.614.214-.707.719-1.485 1.511-2.405.792-.848 1.942-1.343 3.455-1.343 1.438 0 2.447.354 3.166 1.13.648.708 1.079 1.415 1.297 2.193.215.707.358 1.768.358 3.181v.283h-7.845v.282c0 .636.072 1.132.144 1.556.073.494.287.919.504 1.344.288.354.719.707 1.297.99.503.21 1.079.353 1.726.353 1.225 0 2.23-.425 3.093-1.203zm-6.764-4.736h5.759c0-.637-.073-1.202-.145-1.625-.072-.425-.288-.921-.647-1.415-.36-.495-1.008-.777-2.089-.777-.863 0-1.581.282-2.087.989-.504.636-.791 1.557-.791 2.828zM271.685 54.717l-.718 1.415a19.674 19.674 0 00-1.657-.637c-.432-.142-.937-.213-1.512-.213-.719 0-1.295.143-1.728.567a1.959 1.959 0 00-.575 1.413c0 .494.071.848.36 1.132.215.283.719.565 1.438.705l1.656.354c2.231.495 3.31 1.627 3.31 3.393 0 1.202-.431 2.191-1.293 2.899-.865.777-2.017 1.131-3.385 1.131-1.727 0-3.239-.424-4.606-1.202l.72-1.483c1.296.776 2.592 1.201 3.959 1.201.793 0 1.439-.212 1.872-.564.431-.425.718-.921.718-1.558 0-1.061-.718-1.767-2.085-2.049l-1.585-.283c-1.008-.282-1.799-.707-2.375-1.272-.504-.636-.792-1.343-.792-2.121 0-1.131.434-2.05 1.296-2.756.792-.707 1.871-1.062 3.168-1.062a7.879 7.879 0 013.814.99zM249.158 48.921l2.015-.354.215 1.273c0 .354.073.919.073 1.555v2.828l-.073 1.696c.36-.353.936-.777 1.654-1.271a4.972 4.972 0 012.52-.708c.792 0 1.44.213 2.016.566.649.354 1.009.776 1.152 1.201.143.425.215.849.215 1.132 0 .211.073.564.073.848v8.837h-1.944v-8.483c0-.919-.214-1.484-.503-1.838-.36-.353-.792-.495-1.439-.495-1.297 0-2.52.637-3.67 2.05v8.767h-1.944V51.396c-.001-.919-.147-1.698-.36-2.475zM245.559 55.212l-1.08 1.344c-.432-.354-.864-.637-1.152-.778-.358-.141-.719-.211-1.224-.211-.433 0-.936.141-1.367.424-.433.212-.792.636-1.152 1.272-.358.637-.503 1.908-.503 3.747 0 1.343.216 2.403.791 3.182.504.707 1.296 1.061 2.304 1.061 1.007 0 1.943-.424 2.662-1.345l1.079 1.273c-1.079 1.061-2.373 1.625-3.813 1.625-1.8 0-3.095-.635-3.957-1.838-.792-1.13-1.225-2.615-1.225-4.383 0-1.271.145-2.332.503-3.11.288-.849.864-1.625 1.656-2.403.791-.777 1.799-1.132 3.095-1.132.72 0 1.368.071 1.872.283a7.975 7.975 0 011.511.989zM223.246 55.566c1.654-1.132 3.382-1.627 5.18-1.627 1.802 0 2.881.354 3.168 1.132.361.778.505 1.343.575 1.555V58.535l-.07 4.029v.566c0 .776.07 1.343.216 1.697.071.282.359.564.791.778l-1.007 1.343c-.937-.214-1.44-.851-1.729-1.769-1.079 1.132-2.303 1.625-3.6 1.625-.718 0-1.293-.07-1.798-.211a3.148 3.148 0 01-1.151-.706c-.359-.354-.649-.708-.792-1.203a3.227 3.227 0 01-.289-1.343c0-1.415.576-2.476 1.728-3.253 1.152-.776 2.808-1.202 4.895-1.202l.936.072v-.85c0-.493-.07-.918-.07-1.131 0-.283-.217-.636-.505-.989-.287-.354-.792-.495-1.511-.495-.433 0-.792.071-1.153.142-.431.07-.791.213-1.223.354l-.863.424c-.217.142-.432.282-.792.565zm7.052 4.737l-1.007-.071c-.937 0-1.729.071-2.304.212-.576.213-1.008.495-1.439.849-.432.424-.575 1.061-.575 1.839 0 1.555.719 2.262 2.16 2.262.646 0 1.222-.141 1.798-.494.505-.354.936-.85 1.224-1.414zM218.71 54.717l-.719 1.415c-.719-.282-1.225-.495-1.655-.637a5.555 5.555 0 00-1.513-.213c-.72 0-1.295.143-1.727.567-.434.353-.647.847-.647 1.413 0 .494.144.848.359 1.132.288.283.72.565 1.438.705l1.656.354c2.231.495 3.383 1.627 3.383 3.393 0 1.202-.433 2.191-1.295 2.899-.937.777-2.017 1.131-3.455 1.131a9.298 9.298 0 01-4.606-1.202l.791-1.483c1.296.776 2.592 1.201 3.959 1.201.791 0 1.367-.212 1.8-.564.503-.425.72-.921.72-1.558 0-1.061-.649-1.767-2.088-2.049l-1.512-.283c-1.079-.282-1.871-.707-2.374-1.272-.505-.636-.792-1.343-.792-2.121 0-1.131.432-2.05 1.225-2.756.862-.707 1.87-1.062 3.237-1.062s2.591.355 3.815.99zM201.652 54.435l1.942-.565c.146.354.288.637.361.92 0 .21.071.564.071.918v.212c.431-.637 1.009-1.13 1.584-1.484.575-.424 1.224-.565 1.87-.565h.433c.072 0 .143.07.287.142l-.791 2.05c-.216-.07-.432-.07-.575-.07-.792 0-1.44.212-1.873.636-.431.424-.718.777-.792 1.061-.07.284-.143.637-.143 1.131v7.706h-1.943v-9.402c0-.707 0-1.271-.072-1.555-.071-.358-.215-.711-.359-1.135zM196.326 63.978l.791 1.274c-1.152.989-2.59 1.554-4.247 1.554-1.727 0-3.095-.564-4.102-1.768-.936-1.131-1.439-2.756-1.439-4.807 0-1.061.071-1.908.288-2.614.215-.707.719-1.485 1.512-2.405.791-.848 1.943-1.343 3.454-1.343 1.368 0 2.447.354 3.167 1.13.647.708 1.079 1.415 1.295 2.193.217.707.361 1.768.361 3.181v.283h-7.846v.282c0 .636.071 1.132.144 1.556.071.494.289.919.504 1.344.288.354.72.707 1.296.99.503.21 1.079.353 1.728.353 1.222 0 2.23-.425 3.094-1.203zm-6.766-4.736h5.758a9.72 9.72 0 00-.144-1.625c-.072-.425-.289-.921-.648-1.415-.36-.495-1.008-.777-2.087-.777-.864 0-1.584.282-2.087.989-.504.636-.792 1.557-.792 2.828zM182.721 48.852v13.713c0 1.343.074 2.264.074 2.616.071.426.144.708.144.848.072.143.144.354.144.495h-1.944c-.144-.211-.216-.425-.216-.564-.072-.143-.072-.353-.144-.707-.072.141-.217.211-.361.423-.215.142-.503.354-.864.638-.359.282-1.08.354-2.087.354-1.584 0-2.807-.495-3.742-1.628-.864-1.061-1.297-2.614-1.297-4.595 0-2.616.577-4.383 1.872-5.231 1.223-.848 2.304-1.272 3.095-1.272 1.44 0 2.592.566 3.384 1.627v-1.91V48.5zm-1.942 7.987c-.289-.425-.72-.707-1.225-.92-.431-.212-1.08-.353-1.799-.353-1.08 0-1.8.353-2.16.989-.432.707-.719 1.271-.863 1.768a16.382 16.382 0 00-.144 2.12c0 1.061.072 1.909.215 2.403.072.566.36.991.647 1.345.36.354.72.564 1.008.707.288.14.648.14 1.152.14.504 0 .936 0 1.295-.14.36-.143.719-.284 1.008-.495.288-.212.503-.426.575-.565.144-.143.216-.354.289-.425v-6.574zM167.318 63.978l.792 1.274c-1.151.989-2.591 1.554-4.247 1.554-1.728 0-3.095-.564-4.103-1.768-.936-1.131-1.439-2.756-1.439-4.807 0-1.061.072-1.908.288-2.614.288-.707.72-1.485 1.512-2.405.792-.848 1.943-1.343 3.455-1.343 1.439 0 2.446.354 3.167 1.13.648.708 1.152 1.415 1.368 2.193.144.707.288 1.768.288 3.181v.283h-7.846v.282c0 .636.072 1.132.144 1.556.073.494.288.919.576 1.344.216.354.648.707 1.224.99.503.21 1.152.353 1.799.353 1.152 0 2.16-.425 3.022-1.203zm-6.765-4.736h5.758a9.72 9.72 0 00-.144-1.625c-.073-.425-.288-.921-.648-1.415-.36-.495-1.007-.777-2.015-.777-.936 0-1.656.282-2.159.989-.504.636-.792 1.557-.792 2.828zM152.708 48.921c.433 0 .792.141 1.08.423.289.284.432.638.432 1.062 0 .424-.144.778-.432 1.132-.288.282-.647.424-1.08.424-.432 0-.791-.142-1.08-.424-.288-.354-.503-.637-.503-1.061 0-.426.144-.778.432-1.133.287-.282.647-.423 1.151-.423zm-1.081 5.373l2.088-.354v12.584h-2.088zM134.786 49.698h2.375l5.687 10.745c.288.495.576 1.131.936 1.909.288.708.575 1.272.719 1.625-.072-.424-.072-1.2-.144-2.332s-.072-2.05-.072-2.828l-.144-9.119h2.087v16.825h-2.159l-5.543-10.321c-.575-1.061-.935-1.838-1.223-2.403-.288-.565-.576-1.132-.719-1.627.072 1.132.144 1.98.144 2.476.072.494.072 1.202.072 2.12l.072 9.756h-2.087V49.698zM130.755 54.223l-.648 1.484h-2.591v7.847c0 .706.144 1.202.36 1.484.216.214.648.354 1.223.354.504 0 .936-.072 1.225-.212l.288 1.201c-.648.354-1.368.495-2.16.495-.647 0-1.223-.142-1.871-.424-.648-.354-1.008-1.133-1.008-2.333v-8.413h-1.656v-1.484h1.656v-.141c0-.566.072-1.415.215-2.616v-.282l2.088-.425c-.072.425-.144.919-.216 1.555-.072.566-.072 1.202-.072 1.909h3.167zM120.318 54.717l-.792 1.415c-.648-.282-1.151-.495-1.656-.637a4.696 4.696 0 00-1.439-.213c-.72 0-1.295.143-1.728.567-.432.353-.647.847-.647 1.413 0 .494.144.848.359 1.132.216.283.721.565 1.439.705l1.656.354c2.231.495 3.382 1.627 3.382 3.393 0 1.202-.503 2.191-1.367 2.899-.864.777-2.015 1.131-3.382 1.131-1.728 0-3.239-.424-4.607-1.202l.792-1.483c1.223.776 2.591 1.201 3.959 1.201.719 0 1.367-.212 1.799-.564.503-.425.719-.921.719-1.558 0-1.061-.719-1.767-2.087-2.049l-1.511-.283c-1.08-.282-1.872-.707-2.375-1.272-.576-.636-.792-1.343-.792-2.121 0-1.131.359-2.05 1.223-2.756.792-.707 1.872-1.062 3.167-1.062 1.441.001 2.665.355 3.888.99zM101.244 49.274c1.08 0 2.015.212 2.951.636.936.425 1.872 1.273 2.736 2.545.864 1.272 1.295 3.182 1.295 5.868 0 1.697-.216 3.04-.575 4.101-.36 1.061-1.081 2.05-2.16 2.969-1.079.989-2.447 1.413-4.174 1.413-1.008 0-1.944-.14-2.735-.493-.792-.424-1.728-1.132-2.735-2.264-.936-1.132-1.439-3.11-1.439-6.078 0-2.688.576-4.809 1.799-6.364 1.223-1.556 2.878-2.333 5.037-2.333zm0 1.626c-.792 0-1.583.211-2.231.565-.648.354-1.223.919-1.656 1.768-.432.849-.719 2.333-.719 4.312 0 1.272.144 2.476.359 3.465.144 1.061.433 1.907.792 2.403.288.564.792.99 1.511 1.343.72.354 1.44.564 2.16.564 1.368 0 2.376-.422 2.951-1.2.648-.777 1.007-1.485 1.224-2.264.215-.775.288-1.836.288-3.251 0-2.403-.216-4.1-.648-5.019-.504-.92-1.008-1.626-1.655-2.05-.648-.425-1.439-.636-2.376-.636z"
                                    fill="#020204"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M383.248 12.865h3.169v26.018h-3.169zm13.532 0h4.031l-10.652 12.231 10.58 13.787h-4.031l-10.077-13.787z"
                                    fill="#e30712"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M383.248 12.865h3.169v26.018h-3.169zm13.532 0h4.031l-10.652 12.231 10.58 13.787h-4.031l-10.077-13.787z"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-miterlimit="10"
                                    stroke-width=".04"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M360.505 12.865h6.189c1.654 0 3.023.142 4.103.424 1.008.354 1.942.777 2.806 1.484.793.636 1.369 1.414 1.802 2.333.359.918.575 1.909.575 2.898 0 2.122-.649 3.818-1.942 5.161-1.298 1.273-2.952 1.98-5.111 1.98h-.36c.504.423 1.009.848 1.44 1.343.358.495.862 1.061 1.295 1.626.504.636 1.296 1.838 2.52 3.605 1.151 1.696 2.302 3.464 3.455 5.162h-3.961c-.358-.92-1.222-2.334-2.446-4.243-1.294-1.909-2.446-3.535-3.526-4.878-1.008-1.343-1.728-2.122-2.088-2.333-.433-.212-.936-.282-1.583-.282v11.736h-3.166V12.865zm3.166 2.616v9.685h2.808c2.23 0 3.814-.423 4.751-1.272.935-.849 1.439-2.121 1.439-3.959 0-.777-.289-1.625-.722-2.333-.504-.778-1.15-1.273-1.941-1.626-.865-.282-1.944-.495-3.312-.495zM354.026 15.481h-10.869v8.483h9.072v2.688h-9.072v9.543h11.661v2.688h-14.827V12.865h14.395z"
                                    fill="#e30712"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M354.026 15.481h-10.869v8.483h9.072v2.688h-9.072v9.543h11.661v2.688h-14.827V12.865h14.395z"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-miterlimit="10"
                                    stroke-width=".04"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M304.868 12.865h3.453l3.311 15.201c.432 1.766.72 3.393.937 4.807.215 1.415.358 2.474.504 3.111.144-1.061.358-2.192.576-3.323.214-1.13.504-2.544.935-4.241l3.456-15.554h3.598l3.672 16.119c.358 1.626.646 3.111.862 4.384.288 1.272.433 2.121.505 2.616.144-1.061.358-2.05.503-3.041.145-.989.504-2.616 1.008-5.019l3.312-15.059h3.166l-6.045 26.018h-4.176l-3.238-14.423c-.432-1.768-.72-3.252-.863-4.383-.216-1.131-.433-2.192-.504-3.041-.144.919-.287 1.837-.505 2.828-.144 1.061-.505 2.474-.863 4.313l-3.238 14.706h-4.103zM287.233 12.511l5.109 10.534a130.019 130.019 0 011.727 3.959 28.316 28.316 0 011.152 3.535 96.212 96.212 0 01-.217-3.677c-.144-1.625-.144-2.827-.144-3.534l-.144-10.817h5.11v26.372h-5.614l-4.607-10.11c-1.15-2.546-2.015-4.454-2.52-5.727-.575-1.343-.934-2.262-1.079-2.899.073.849.146 1.98.217 3.605 0 1.557.072 2.9.072 3.889l.145 11.242h-5.184V12.511zM265.208 16.824v6.151h7.63v4.313h-7.63v6.999h10.221v4.596H259.81V12.511h15.258l-.719 4.313z"
                                    fill="#e30712"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M265.208 16.824v6.151h7.63v4.313h-7.63v6.999h10.221v4.596H259.81V12.511h15.258l-.719 4.313z"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-miterlimit="10"
                                    stroke-width=".04"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M248.437 16.966v21.917h-5.469V16.966h-6.551v-4.455h19.361l-.935 4.455z"
                                    fill="#e30712"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M248.437 16.966v21.917h-5.469V16.966h-6.551v-4.455h19.361l-.935 4.455z"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-miterlimit="10"
                                    stroke-width=".04"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M219.142 12.511l5.184 10.534a138.157 138.157 0 011.728 3.959c.431 1.272.863 2.404 1.15 3.535-.07-.849-.145-2.05-.216-3.677-.143-1.625-.217-2.827-.217-3.534l-.07-10.817h5.11v26.372h-5.615l-4.605-10.11c-1.152-2.546-2.015-4.454-2.521-5.727-.575-1.343-.935-2.262-1.078-2.899.07.849.144 1.98.144 3.605.071 1.557.144 2.9.144 3.889l.145 11.242h-5.255V12.511zM197.19 16.824v6.151h7.629v4.313h-7.629v6.999h10.149v4.596h-15.548V12.511h15.26l-.721 4.313z"
                                    fill="#e30712"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M197.19 16.824v6.151h7.629v4.313h-7.629v6.999h10.149v4.596h-15.548V12.511h15.26l-.721 4.313z"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-miterlimit="10"
                                    stroke-width=".04"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M171.854 12.511c.575 0 1.223.072 2.016.072.864.07 1.727.07 2.662.141 2.376.141 4.536 1.273 6.551 3.181 1.943 1.979 2.951 5.302 2.951 9.898 0 3.676-.648 6.434-1.944 8.2-1.294 1.697-2.52 2.898-3.671 3.605-1.151.708-2.375 1.06-3.598 1.06-.289 0-.937.071-1.872.144-1.008 0-1.656.07-1.943.07h-6.55V12.51h5.398zm.072 22.058h2.951c3.383 0 5.11-2.757 5.11-8.271 0-1.696-.072-3.11-.288-4.312-.145-1.202-.504-2.192-1.007-3.041-.432-.777-1.007-1.343-1.728-1.697-.647-.354-1.512-.566-2.52-.566h-2.519v17.887zM145.654 12.511v17.321c0 1.556.217 2.758.72 3.605.432.92 1.584 1.344 3.383 1.344 1.152 0 2.087-.212 2.807-.778.72-.495 1.152-1.131 1.224-1.837.072-.636.145-1.556.145-2.757V12.512h5.47v19.796a10.64 10.64 0 01-.576 2.475c-.36 1.06-1.295 2.051-2.808 3.04-1.438.991-3.526 1.556-6.117 1.556-4.247 0-6.838-.778-7.774-2.262-1.007-1.556-1.583-2.616-1.728-3.394-.216-.707-.288-1.697-.288-2.828V12.513h5.542zM128.956 16.966v21.917h-5.471V16.966h-6.549v-4.455h19.289l-.864 4.455z"
                                    fill="#e30712"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M128.956 16.966v21.917h-5.471V16.966h-6.549v-4.455h19.289l-.864 4.455z"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-miterlimit="10"
                                    stroke-width=".04"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M110.89 18.168a22.04 22.04 0 00-3.239-1.555 10.72 10.72 0 00-3.023-.425c-1.079 0-1.943.283-2.663.848-.72.495-1.08 1.273-1.08 2.191 0 .637.216 1.203.648 1.556.432.424 1.224.777 2.375 1.06l3.239.848c4.534 1.273 6.766 3.889 6.766 7.848 0 2.687-1.008 4.878-3.022 6.434-2.016 1.625-4.679 2.474-8.062 2.474-1.584 0-3.095-.211-4.679-.635a20.257 20.257 0 01-4.461-1.769l2.015-4.101a29.456 29.456 0 003.814 1.556c1.08.353 2.231.565 3.527.565 3.167 0 4.751-1.202 4.751-3.605 0-1.696-1.151-2.828-3.527-3.464l-2.951-.778c-1.368-.354-2.447-.777-3.311-1.343-.864-.495-1.583-1.272-2.159-2.263-.648-.99-.936-2.262-.936-3.747 0-2.404.864-4.312 2.663-5.867 1.799-1.486 4.103-2.263 6.909-2.263 1.656 0 3.312.212 4.823.707 1.584.424 2.951 1.131 4.175 1.979zM74.902 11.522c-1.297 15.978-2.592 23.26-4.32 30.046-1.511 6.079-4.605 12.019-9.788 16.755-4.391 4.029-9.357 6.715-15.834 8.554-5.543 1.556-13.533 2.687-20.082 3.465-5.398.636-9.861.989-11.3 1.061l-.504.425c5.327-.072 38.22-.072 38.507-.143 21.665-.212 23.537-18.381 23.68-22.481.072-3.252.072-25.167.072-34.642v-3.534z"
                                    fill="#e30712"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M61.082 58.605c5.182-4.807 8.349-10.815 9.861-16.896 1.727-6.927 3.095-14.351 4.39-30.682l-3.239 1.556c-1.007 14.775-2.664 22.057-4.318 28.35-2.52 9.402-10.365 18.945-23.752 23.327-4.535 1.486-12.452 2.689-19.075 3.396-3.31.423-6.333.637-8.564.848-.648.071-1.224.071-1.728.142l-1.583 3.183s20.874-1.485 31.958-4.596c6.549-1.84 11.587-4.527 16.05-8.628z"
                                    fill="#c00b0b"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M42.08 2.191c-6.333 0-10.508.069-13.315.212-7.918.564-13.82 3.251-17.49 7.281-5.254 5.797-6.19 11.453-6.55 15.553-.145 1.485-.145 6.293-.145 12.09 0 7.707.072 17.25.072 22.623v.282h.36c1.583-15.764 2.52-20.854 4.463-27.43 3.238-10.958 11.3-21.421 25.839-25.522 11.085-3.11 27.855-4.594 27.855-4.594v-.354c-8.853-.141-15.763-.141-21.089-.141z"
                                    fill="#e30712"
                                />
                                <path
                                    clip-path="url(#b)"
                                    d="M5.084 25.309c.36-4.101 1.295-9.615 6.478-15.342 3.599-3.958 9.357-6.646 17.203-7.14 5.183-.354 15.188-.354 34.333-.141h.072l1.44-3.041-1.512-.07c-5.326.07-34.045.142-34.333.142-5.902.071-10.653 1.343-14.396 3.393-5.615 3.181-8.781 7.917-10.436 12.23-1.656 4.241-1.944 8.059-2.016 9.897-.072 3.393-.072 25.24-.072 34.712v1.766l3.167-1.484v-.282c0-9.472-.216-31.388.072-34.64z"
                                    fill="#c00b0b"
                                />
                            </g>
                        </svg>
                    </div>
                    <div class="instructions">
                        <div class="number">1</div>
                        <div class="instruction">
                            Scanne den QR-Code oder besuche <strong>checkin.stw-on.de</strong>
                            <div class="translation">Scan the QR code or visit checkin.stw-on.de</div>
                        </div>
                        <div class="number">2</div>
                        <div class="instruction">
                            Gib deine Kontaktdaten ein und sende sie ab
                            <div class="translation">Enter your contact details and submit them</div>
                        </div>
                        <div class="number">3</div>
                        <div class="instruction">
                            Lasse die Bestätigung zur Überprüfung geöffnet
                            <div class="translation">Keep the confirmation screen open for verification</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('qr-code').innerHTML = new QRCode({
                content: SHERIFF_QR_URL,
                ecl: 'M',
                join: true,
                container: 'svg-viewbox',
                padding: 0,
            }).svg()
        </script>
    </body>
</html>
