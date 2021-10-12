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

            .location-name {
                padding-top: 3mm;
                letter-spacing: 0.5mm;
                color: #9f9f9f;
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
                        <div class="location-name">{{ $location->name }}</div>
                        <div class="spacer"></div>
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.0" viewBox="0 0 77.97 14.55" class="logo">
                            <path d="M56.40749999999999 10.929899999999998l.3798-.0973c.0582.1262.1005.2525.1005.3788.2374-.2526.4908-.3788.7598-.3788.1267 0 .2534.0263.351.0973.1135.071.1979.1394.2402.2394a.834.834 0 01.0553.3077v1.852h-.3798v-1.6547c0-.1262-.0133-.2104-.0133-.2683a.5984.5984 0 00-.0844-.1526c-.0581-.0578-.1425-.0841-.2692-.0841-.0977 0-.2111.0263-.3378.0973-.1265.071-.2374.1394-.3218.2394v1.823h-.3801v-1.823c0-.1552-.0158-.2815-.0158-.3367-.0133-.0579-.0424-.142-.0844-.2394zM55.381 12.8239l.1555.2525c-.2269.1973-.5066.3078-.8311.3078-.3378 0-.6042-.1105-.8024-.3499-.182-.2236-.2822-.5471-.2822-.9549 0-.2104.0158-.3787.0582-.5182.042-.1394.1397-.2946.2954-.4761.153-.1683.38-.2683.6757-.2683.2797 0 .4775.071.6175.2262.1264.1394.211.2789.2531.434.0424.1395.0714.35.0714.6314v.0552h-1.5333v.0579c0 .1263.0133.2236.0266.3078.0158.0973.0578.1815.1001.2657.0554.071.1397.142.2535.1972a.8487.8487 0 00.3375.071c.2376 0 .4355-.0841.6045-.2393zm-1.3222-.9391h1.1241c0-.1263-.013-.2394-.0262-.3236-.0158-.0842-.0582-.1815-.1268-.2815-.071-.0973-.1978-.1525-.4089-.1525-.169 0-.3087.0552-.4092.1947-.0977.1262-.153.3103-.153.5629zM52.7922 10.9851l-.1425.2815a4.3302 4.3302 0 00-.322-.1263c-.0844-.0289-.1846-.042-.2955-.042-.1425 0-.2535.0289-.3378.113-.0712.0684-.1135.1684-.1135.2789 0 .1.0133.1683.0714.2262.042.0553.1397.1105.2797.1394l.3245.071c.4355.0974.6466.321.6466.6735 0 .2368-.0847.434-.2534.5735-.1688.1552-.3932.2262-.6596.2262-.3378 0-.6336-.0842-.9025-.2394l.1425-.2946c.2532.1552.5066.2394.773.2394.1558 0 .2825-.0421.3669-.1132.0844-.0841.14-.1815.14-.3077 0-.2105-.14-.3525-.4092-.4078l-.3088-.0552c-.1978-.0579-.3508-.142-.4642-.2525-.0977-.1263-.1558-.2683-.1558-.421 0-.2261.0843-.4077.2534-.547.1555-.1421.3666-.2105.62-.2105.2665 0 .5199.0684.7468.1946zM48.3853 9.8356l.396-.071c.013.0841.0262.1683.042.2525 0 .071.0133.1841.0133.3104v.5603l-.0133.3367c.0686-.071.182-.1552.322-.2525.1426-.0842.3113-.1394.4934-.1394.1557 0 .2825.042.3934.1105.1264.071.1978.1551.2269.2393.0262.0842.042.1684.042.2236 0 .0421.0133.1131.0133.1684v1.7546h-.3799v-1.6836c0-.1815-.0423-.2946-.0976-.3657-.0714-.0683-.1558-.0973-.2825-.0973-.2532 0-.4934.1263-.7177.4078v1.7388h-.3802v-3.0015c0-.1842-.0288-.3367-.0711-.492zM47.6834 11.0851l-.211.2657c-.0845-.071-.1691-.1263-.227-.1552-.0686-.0263-.14-.0421-.2376-.0421-.0844 0-.1846.029-.2693.0842-.0843.042-.153.1262-.224.2525-.0714.1263-.0977.3788-.0977.7444 0 .2657.042.4762.153.6314.1002.1394.2534.2104.4512.2104.1979 0 .38-.0842.5199-.2657l.2111.2526c-.2111.2104-.4645.3209-.7442.3209-.3536 0-.6068-.1263-.7759-.363-.153-.2262-.2373-.5209-.2373-.8707 0-.2526.0263-.463.0977-.6182.0553-.1684.1687-.321.3245-.4761.153-.1553.3508-.2236.6042-.2236.14 0 .2665.0131.367.0552.0843.0421.1952.1131.2954.1973zM43.3189 11.1535c.3245-.2236.662-.321 1.0131-.321.3537 0 .5648.0684.62.2237.0715.1552.0977.2657.1135.3077v.3788l-.0158.7997v.1131c0 .1552.0158.2657.0423.3367.0158.0553.0712.1132.1558.1552l-.198.2657c-.1821-.042-.2798-.1683-.338-.3498-.211.2236-.4484.3209-.7018.3209-.1425 0-.2531-.0132-.3536-.042-.0844-.0264-.1688-.0843-.2241-.1395-.0714-.071-.1267-.1394-.1558-.2394a.6385.6385 0 01-.0553-.2657c0-.2814.1134-.4919.3375-.6445.2244-.1552.549-.2393.9582-.2393l.182.0131v-.1684c0-.0973-.0133-.1815-.0133-.2235 0-.0553-.0423-.1263-.1001-.1973-.0556-.0684-.1533-.0974-.2958-.0974-.0844 0-.153.0132-.2241.029-.0847.0131-.1558.042-.2402.0684-.0843.042-.14.071-.169.0842-.042.0289-.0844.0578-.153.113zm1.38.9417l-.1981-.0158c-.182 0-.3375.0158-.4485.0421-.1134.0421-.1978.1-.2825.1684-.0843.0842-.1134.2104-.1134.3656 0 .3078.1425.4499.4222.4499.1267 0 .2402-.029.3536-.1.0977-.0684.182-.1683.2377-.2788zM42.4321 10.9851l-.14.2815c-.1397-.0552-.2401-.0973-.3245-.1263a1.0294 1.0294 0 00-.2955-.042c-.14 0-.2534.0289-.3378.113-.0844.0684-.1268.1684-.1268.2789 0 .1.0291.1683.0714.2262.0554.0553.1397.1105.2823.1394l.322.071c.438.0974.6624.321.6624.6735 0 .2368-.0844.434-.2535.5735-.182.1552-.3931.2262-.6753.2262-.3249 0-.6333-.0842-.9-.2394l.153-.2946c.2534.1552.5068.2394.7758.2394.1532 0 .2667-.0421.351-.1132.0977-.0841.14-.1815.14-.3077 0-.2105-.1267-.3525-.4064-.4078l-.2957-.0552c-.2111-.0579-.3667-.142-.4643-.2525-.1005-.1263-.1558-.2683-.1558-.421 0-.2261.0844-.4077.2402-.547.1688-.1421.364-.2105.6333-.2105.2664 0 .5066.0684.7442.1946zM39.0965 10.9299l.3802-.1131c.0288.071.0578.1263.0711.1841 0 .0421.0133.1105.0133.1815v.0421c.0843-.1263.1978-.2236.3113-.2946.1109-.0842.2376-.1131.364-.1131h.0847c.0158 0 .0288.0158.0578.029l-.1555.4076c-.0423-.0157-.0846-.0157-.1134-.0157-.1533 0-.28.042-.3644.1262-.0843.0842-.1425.1552-.1555.2105-.0132.0578-.029.1263-.029.2262v1.5284h-.3802v-1.8651c0-.142 0-.2525-.013-.3104-.0132-.0684-.0423-.1394-.0714-.2236zM38.0568 12.8239l.153.2525c-.2241.1973-.5066.3078-.8286.3078-.3376 0-.6068-.1105-.8021-.3499-.1849-.2236-.2825-.5471-.2825-.9549 0-.2104.0133-.3787.0556-.5182.042-.1394.1425-.2946.2955-.4761.1558-.1683.3799-.2683.6756-.2683.269 0 .48.071.62.2262.1268.1394.2112.2789.2535.434.042.1395.0711.35.0711.6314v.0552h-1.5358v.0579c0 .1263.0158.2236.029.3078.0133.0973.0554.1815.0977.2657.0581.071.1425.142.2534.1972.1002.0421.2111.071.3376.071.2401 0 .4383-.0841.607-.2393zm-1.3247-.9391h1.1269c0-.1263-.0133-.2394-.029-.3236-.0133-.0842-.0554-.1815-.1268-.2815-.0686-.0973-.1953-.1525-.4064-.1525-.1688 0-.3113.0552-.409.1947-.0976.1262-.1557.3103-.1557.5629zM35.3969 9.8224v2.72c0 .2683.0132.4498.0132.5209.013.0841.0291.1394.0291.1683.013.0263.0263.0684.0263.0974h-.38c-.0264-.0421-.0422-.0842-.0422-.1132-.0133-.0263-.0133-.0684-.0263-.1394-.016.029-.0423.0421-.0714.0842a3.7903 3.7903 0 00-.1688.1263c-.0714.0552-.211.071-.4092.071-.3087 0-.5486-.1-.731-.3235-.1687-.2105-.2531-.5183-.2531-.9129 0-.5182.1135-.868.3666-1.0364.2376-.1683.4487-.2525.6045-.2525.2823 0 .5066.1105.6624.3209v-.3788-1.0233zm-.3799 1.5836c-.0581-.0841-.1425-.1394-.2402-.1815-.0846-.042-.211-.071-.351-.071-.2112 0-.3537.071-.4223.1973-.0843.1394-.1425.2525-.1687.3499a3.0061 3.0061 0 00-.029.4208c0 .2105.0132.3788.042.4788.0132.1105.0714.1947.1267.2657.0714.071.14.113.1978.1394.0556.029.1267.029.2244.029.0976 0 .182 0 .2534-.029.0711-.0263.1397-.0552.1979-.0973.0553-.0421.0976-.0842.1109-.1131.029-.0264.042-.0684.0581-.0842zM32.3833 12.8239l.1558.2525c-.2244.1973-.5069.3078-.8314.3078-.3379 0-.6043-.1105-.8021-.3499-.182-.2236-.2797-.5471-.2797-.9549 0-.2104.013-.3787.0553-.5182.0553-.1394.14-.2946.2955-.4761.1558-.1683.3802-.2683.6757-.2683.2822 0 .4775.071.62.2262.1267.1394.2244.2789.2667.434.0288.1395.0553.35.0553.6314v.0552h-1.5333v.0579c0 .1263.0133.2236.029.3078.0133.0973.0557.1815.111.2657.0423.071.1267.142.2402.1972a.9161.9161 0 00.3508.071c.2272 0 .4224-.0841.5912-.2393zm-1.3222-.9391h1.127c0-.1263-.0159-.2394-.0292-.3236-.0132-.0842-.0553-.1815-.1267-.2815-.0711-.0973-.1978-.1525-.3931-.1525-.1846 0-.3246.0552-.4222.1947-.1002.1262-.1558.3103-.1558.5629zM29.528 9.8356c.0845 0 .153.029.2112.0842.0553.0552.0843.1262.0843.2104s-.029.1552-.0843.2236c-.0582.0579-.1268.0842-.2111.0842-.0844 0-.1558-.0263-.2111-.0842-.0582-.0684-.1002-.1263-.1002-.2105 0-.0841.0288-.1525.0844-.2235.0581-.0553.1267-.0842.2269-.0842zm-.211 1.0653l.4064-.0683v2.4964h-.4064zM26.0237 9.9908h.4644l1.111 2.1307c.0554.1.1134.2263.182.3788.058.142.1135.2526.1425.3236-.0158-.0842-.0158-.2394-.029-.463-.0132-.2236-.0132-.4077-.0132-.5603l-.029-1.8098h.409v3.3382h-.4222l-1.0846-2.0493c-.1134-.2104-.182-.363-.2401-.476-.0554-.1132-.1109-.2237-.1399-.3236.0132.2262.029.3945.029.4919.0132.0973.0132.2394.0132.4209l.0132 1.936h-.4063zM25.2346 10.8878l-.1266.2946h-.5067v1.5573c0 .1394.029.2394.0713.2946.0422.0421.1266.071.2375.071.1002 0 .1847-.0157.2401-.042l.0554.2367c-.1267.071-.2665.1-.4222.1-.1267 0-.2375-.029-.3642-.0842-.1266-.071-.1979-.2262-.1979-.463v-1.6704h-.3246v-.2946h.3246v-.029c0-.1104.0132-.2788.0422-.5182v-.0552l.409-.0842c-.0158.0842-.029.1815-.0422.3078-.0158.1131-.0158.2394-.0158.3788zM23.1948 10.9851l-.1557.2815c-.1267-.0552-.2243-.0973-.3246-.1263-.0844-.0289-.182-.042-.2797-.042-.1425 0-.2533.0289-.3378.113-.0844.0684-.1266.1684-.1266.2789 0 .1.0264.1683.0686.2262.0422.0553.1425.1105.2824.1394l.3245.071c.4354.0974.6597.321.6597.6735 0 .2368-.0976.434-.2665.5735-.1689.1552-.3932.2262-.6623.2262-.3378 0-.6334-.0842-.8999-.2394l.1557-.2946c.2375.1552.5067.2394.7732.2394.1398 0 .2665-.0421.351-.1132.1002-.0841.1425-.1815.1425-.3077 0-.2105-.1425-.3525-.409-.4078l-.2956-.0552c-.2111-.0579-.3668-.142-.4645-.2525-.1134-.1263-.1557-.2683-.1557-.421 0-.2261.0713-.4077.2402-.547.1557-.1421.3668-.2105.6201-.2105.2797 0 .5199.0684.76.1946zM19.4635 9.9066c.211 0 .3958.0421.5779.1263.182.0841.3668.2525.5356.505.169.2526.2534.6314.2534 1.1628 0 .3366-.0422.605-.1135.8154-.0712.2105-.211.4051-.4222.5893-.2111.1946-.4776.2788-.8154.2788-.198 0-.38-.0263-.5357-.0973-.1557-.0842-.3378-.2236-.5357-.4499-.182-.2236-.2797-.6155-.2797-1.2048 0-.534.1108-.9548.351-1.2626.24-.3104.562-.463.9843-.463zm0 .321a.9088.9088 0 00-.4355.113c-.1266.071-.2401.1815-.3245.35-.0845.1683-.1399.4629-.1399.8575 0 .2525.029.4892.0712.6865.0264.2105.0845.3788.1531.4762.058.1131.1557.1973.2956.2683.1425.0684.2823.1105.4222.1105.2691 0 .4644-.0842.5779-.2368.1266-.1552.1979-.2946.2401-.4498.0422-.1552.0555-.3657.0555-.6445 0-.4788-.0423-.8154-.1267-.997-.0977-.1815-.198-.3235-.3246-.4077-.1267-.0842-.2797-.1263-.4644-.1263z" fill="#020204"/>
                            <path d="M74.6049 2.6805h.6203v5.1638h-.6203V2.6805zm2.647 0h.7888l-2.0845 2.428 2.0686 2.7358h-.7862l-1.9713-2.7358 1.9845-2.428zM70.1585 2.6805h1.2113c.322 0 .591.029.802.0842.1953.071.3802.1552.549.2946.1532.1263.2667.2814.351.463.0712.1815.1135.3787.1135.576 0 .421-.1267.7576-.3802 1.0233-.2531.2526-.5777.392-.9999.392h-.0714c.1005.0842.1982.1684.2825.2683.0712.0973.1688.2104.2532.321.0976.1262.2534.3656.4936.7154.2244.3367.4485.6892.6756 1.026h-.7758c-.0688-.1842-.2376-.463-.4778-.8418-.2531-.3788-.4775-.7024-.6886-.968-.1981-.2684-.3378-.421-.4092-.463-.0844-.0421-.182-.058-.3088-.058v2.3308h-.62zm.62.5182v1.923h.549c.4354 0 .7442-.0842.929-.2526.182-.1684.2797-.4209.2797-.7865 0-.1526-.0556-.321-.14-.463-.0976-.1526-.2244-.2525-.3799-.321-.169-.0578-.3801-.0999-.6465-.0999zM66.1475 2.6805h2.8155l-.0711.5182h-2.1243v1.6835h1.7732v.534h-1.7732v1.894h2.28v.534h-2.9zM59.2785 2.6805h.6757l.649 3.0172c.0845.3499.1398.6734.1821.9523.0423.2815.0711.4919.0977.6182.029-.2105.071-.434.1134-.6603.0423-.2236.0977-.505.182-.8418l.6757-3.0856h.7044l.718 3.1987c.0711.3236.1264.6182.1687.8707.0554.2526.0844.421.0977.5183.029-.2105.0711-.4078.1002-.6024.0265-.1973.0976-.5209.1953-.997l.6491-2.9883h.6175l-1.1822 5.1638h-.8154l-.6333-2.862c-.0844-.3526-.1397-.6472-.1687-.8708-.0424-.2236-.0844-.434-.0977-.6024-.029.1815-.0581.363-.1005.5603-.0262.2104-.0976.492-.1687.855l-.6333 2.9199h-.8024zM55.8323 2.6095l.9974 2.0912c.1267.2815.2401.5472.3378.7866.1002.2525.1688.4761.2269.6997-.0158-.1684-.029-.405-.0423-.7287-.0291-.3235-.0291-.5603-.0291-.7023l-.0288-2.1465h1v5.2348h-1.0976l-.9-2.0072c-.227-.505-.3957-.8838-.4934-1.1364-.1134-.2656-.1823-.4498-.211-.576.013.1683.0287.3945.042.7155 0 .3104.0133.576.0133.7733l.029 2.2308h-1.0134V2.6095zM53.452 2.6095l-.14.8575h-1.7864v1.2206h1.491v.855h-1.491v1.3889h1.9976v.9128H50.47V2.6095zM49.681 2.6095l-.182.8838h-1.2537v4.351h-1.0685v-4.351h-1.2827v-.8838zM42.5165 2.6095L43.53 4.7007c.1264.2815.2398.5472.3375.7866.0847.2525.169.4761.2269.6997-.0158-.1684-.0288-.405-.042-.7287-.0291-.3235-.0424-.5603-.0424-.7023l-.0158-2.1465h1.0002v5.2348h-1.0978l-.8998-2.0072c-.2269-.505-.396-.8838-.4936-1.1364-.1134-.2656-.182-.4498-.211-.576.0132.1683.029.3945.029.7155.0133.3104.0265.576.0265.7733l.0288 2.2308h-1.0264V2.6095zM40.152 2.6095l-.1397.8575h-1.7867v1.2206h1.491v.855h-1.491v1.3889h1.9846v.9128H37.17V2.6095zM33.27 2.6095c.1135 0 .2402.0157.3957.0157.169.0132.3378.0132.5199.0263.4645.029.8867.2526 1.2799.6314.3801.3946.578 1.0522.578 1.965 0 .7287-.1268 1.2758-.3802 1.6283-.2532.3367-.4933.5735-.7177.7155-.2244.1394-.4645.2105-.7044.2105-.0556 0-.1823.0131-.3644.0263-.1978 0-.3245.0158-.3798.0158h-1.2825V2.6095zm.0158 4.3772h.5752c.6624 0 1.0002-.5472 1.0002-1.6415 0-.3367-.0133-.6155-.0553-.8549-.029-.2394-.1005-.434-.1981-.6024-.0844-.1552-.1979-.2683-.3378-.3367-.1265-.071-.2955-.1131-.4934-.1131h-.4908zM28.148 2.6095v3.4381c0 .3078.0422.5471.1397.7155.0847.1815.3116.2657.6624.2657.2243 0 .4092-.0421.5489-.1526.14-.1.2244-.2262.2402-.3656.0132-.1263.0262-.3078.0262-.5472v-3.354h1.0716V6.5396c-.0158.1263-.0423.2946-.1134.4893-.0714.2104-.2535.4077-.549.605-.2825.1947-.6889.3078-1.1955.3078-.8312 0-1.3378-.1552-1.52-.4498-.1978-.3078-.3113-.5183-.3377-.6735-.0422-.1394-.058-.3367-.058-.5603V2.6095zM26.3034 2.6095l-.169.8838h-1.2507v4.351h-1.0714v-4.351h-1.2798v-.8838zM21.3503 3.7327c-.2402-.1394-.4513-.2394-.6334-.3078a2.0194 2.0194 0 00-.5911-.0842c-.2111 0-.38.0553-.5199.1684-.1424.0973-.211.2525-.211.434 0 .1263.0422.2394.1266.3078.0844.0842.2375.1552.4645.2105l.6333.1683c.8866.2525 1.322.7734 1.322 1.5573 0 .534-.1979.968-.591 1.2785-.3932.3209-.9157.4892-1.5755.4892-.3113 0-.6069-.042-.9156-.1262-.3088-.0842-.5912-.1947-.8735-.3499l.3958-.8128c.2797.1394.533.2367.7442.3077.211.071.438.1132.6914.1132.6175 0 .9288-.2394.9288-.7156 0-.3367-.227-.5629-.6913-.6891l-.5753-.1526c-.2692-.071-.4803-.1552-.6492-.2684-.1689-.0973-.3087-.2525-.4222-.4471-.1266-.1973-.182-.4499-.182-.7445 0-.4761.1688-.855.5198-1.1653.3536-.2946.8022-.4472 1.351-.4472.3247 0 .6492.042.9448.1394.3088.0842.5753.2236.8154.392zM14.3124 2.4148c-.2533 3.1698-.5067 4.614-.8444 5.9608-.2956 1.2075-.8999 2.386-1.9132 3.325-.8576.7997-1.8287 1.3337-3.0954 1.6994-1.0845.3078-2.6468.5314-3.9266.6866-1.0555.1262-1.929.1973-2.2113.2104l-.0977.0842c1.0424-.0132 7.4732-.0132 7.5287-.029 4.238-.042 4.6021-3.6459 4.6312-4.4614.0132-.6445.0132-4.9928.0132-6.8736v-.7024z" fill="#e30712"/>
                            <path d="M11.6102 11.7585c1.0133-.9549 1.6335-2.1465 1.929-3.354.3378-1.3757.6043-2.8488.8577-6.0897l-.6334.3104c-.1952 2.9305-.5198 4.3773-.8444 5.6242-.4908 1.8676-2.0266 3.7616-4.6444 4.6297-.8866.2947-2.433.534-3.7287.6735-.6465.0841-1.2376.1262-1.6757.1683-.1266.0158-.2375.0158-.3377.029l-.3088.6313s4.0823-.2946 6.2488-.9128c1.2799-.363 2.2668-.897 3.1376-1.7099z" fill="#c00b0b"/>
                            <path d="M7.8947.5629c-1.2376 0-2.053.0131-2.6019.042-1.549.1106-2.7022.6446-3.42 1.4442C.8437 3.1987.6616 4.322.5904 5.1348.564 5.4294.564 6.3843.564 7.5338c0 1.531.0132 3.425.0132 4.4904v.0553h.0712c.3088-3.1278.4908-4.138.8708-5.4427.6334-2.1754 2.2114-4.251 5.0534-5.0638C8.7392.9548 12.0193.6602 12.0193.6602v-.071C10.2882.5629 8.937.5629 7.8947.5629z" fill="#e30712"/>
                            <path d="M.6616 5.1506C.733 4.335.915 3.2408 1.9283 2.1044 2.6328 1.3204 3.757.7864 5.2928.6892c1.0133-.071 2.9687-.071 6.7132-.029h.0133l.2823-.6024L12.006.042c-1.0423.0158-6.6578.029-6.7132.029C4.137.084 3.2081.3367 2.4771.7444 1.3794 1.3757.7593 2.3148.4373 3.1724c-.3246.8418-.38 1.5994-.3958 1.9624-.0132.6734-.0132 5.0085-.0132 6.8894v.3499l.6201-.2946v-.0553c0-1.8809-.0422-6.2291.0132-6.8736z" fill="#c00b0b"/>
                        </svg>
                    </div>
                    <div class="instructions">
                        <div class="number">1</div>
                        <div class="instruction">
                            Scanne den QR-Code oder besuche <strong>{{ config('sheriff.host') }}</strong>
                            <div class="translation">Scan the QR code or visit {{ config('sheriff.host') }}</div>
                        </div>
                        <div class="number">2</div>
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
