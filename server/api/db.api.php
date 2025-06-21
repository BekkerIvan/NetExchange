<?php
require_once "../requires.php";

$APIObj = new API([
    "sync" => API::GET,
    "currency" => API::GET
]);

if (!$APIObj->validate()) {
    $APIObj->return("Invalid request");
}

$APIObj->run();

function sync(): string {
    $ModelFileObjArr = new RecursiveDirectoryIterator("../assets/php/models/", FilesystemIterator::SKIP_DOTS);

    foreach($ModelFileObjArr as $ModelFileObj) {
        if ($ModelFileObj->isDir()) {
            continue;
        }
        if ($ModelFileObj->getExtension() !== "php") {
            continue;
        }
        $ModelIdentifierStr = ".model.php";
        if (!str_contains($ModelFileObj->getFileName(), $ModelIdentifierStr)) {
            continue;
        }

        $ClassNameStr = str_replace($ModelIdentifierStr, "", $ModelFileObj->getFilename());
        require_once $ModelFileObj->getRealPath();
        if (!class_exists($ClassNameStr)) {
            continue;
        }

        /** @var Model $ModelObj */
        $ModelObj = new $ClassNameStr();
        $ModelObj->setTableColumns();
        $ModelObj->setTableConstraints();
        $ModelObj->setTableDefaults();
    }

    Model::createDataModel(new Database());
    return "success";
}

function currency(API $APIObj): string {
    $ApiKeyStr = getenv("CURRENCY_API_KEY");
    $BaseCurrencyStr = BASE_CURRENCY;

    $ResponseStr = $APIObj->call("https://apilayer.net/api/live?access_key={$ApiKeyStr}&source={$BaseCurrencyStr}");
    $ResponseObj = json_decode($ResponseStr);
//    $ResponseObj = json_decode('{"success":true,"terms":"https:\\/\\/currencylayer.com\\/terms","privacy":"https:\\/\\/currencylayer.com\\/privacy","timestamp":1750369625,"source":"ZAR","quotes":{"ZARAED":0.202833,"ZARAFN":3.89372,"ZARALL":4.725161,"ZARAMD":21.253986,"ZARANG":0.098845,"ZARAOA":50.648186,"ZARARS":64.3087,"ZARAUD":0.085271,"ZARAWG":0.099418,"ZARAZN":0.09387,"ZARBAM":0.094068,"ZARBBD":0.111416,"ZARBDT":6.748974,"ZARBGN":0.093994,"ZARBHD":0.02084,"ZARBIF":162.438658,"ZARBMD":0.055232,"ZARBND":0.071098,"ZARBOB":0.382698,"ZARBRL":0.303287,"ZARBSD":0.055184,"ZARBTC":5.29194e-7,"ZARBTN":4.786641,"ZARBWP":0.744214,"ZARBYN":0.18059,"ZARBYR":1082.556168,"ZARBZD":0.110843,"ZARCAD":0.075688,"ZARCDF":158.903783,"ZARCHF":0.045081,"ZARCLF":0.001354,"ZARCLP":51.978182,"ZARCNY":0.397088,"ZARCNH":0.3969,"ZARCOP":225.900199,"ZARCRC":27.871982,"ZARCUC":0.055232,"ZARCUP":1.46366,"ZARCVE":5.30922,"ZARCZK":1.191182,"ZARDJF":9.815923,"ZARDKK":0.358381,"ZARDOP":3.278056,"ZARDZD":7.215348,"ZAREGP":2.798572,"ZARERN":0.828487,"ZARETB":7.459129,"ZAREUR":0.048046,"ZARFJD":0.124599,"ZARFKP":0.041006,"ZARGBP":0.041018,"ZARGEL":0.150252,"ZARGGP":0.041006,"ZARGHS":0.569021,"ZARGIP":0.041006,"ZARGMD":3.94911,"ZARGNF":478.092155,"ZARGTQ":0.42416,"ZARGYD":11.545288,"ZARHKD":0.433566,"ZARHNL":1.444355,"ZARHRK":0.362016,"ZARHTG":7.237262,"ZARHUF":19.371138,"ZARIDR":904.762887,"ZARILS":0.192856,"ZARIMP":0.041006,"ZARINR":4.790714,"ZARIQD":72.354519,"ZARIRR":2326.667264,"ZARISK":6.851017,"ZARJEP":0.041006,"ZARJMD":8.802257,"ZARJOD":0.039159,"ZARJPY":8.032622,"ZARKES":7.15307,"ZARKGS":4.8301,"ZARKHR":222.034495,"ZARKMF":23.612016,"ZARKPW":49.709653,"ZARKRW":76.079937,"ZARKWD":0.016919,"ZARKYD":0.045988,"ZARKZT":28.724927,"ZARLAK":1191.640296,"ZARLBP":4948.828179,"ZARLKR":16.589962,"ZARLRD":11.02716,"ZARLSL":0.989766,"ZARLTL":0.163087,"ZARLVL":0.03341,"ZARLYD":0.299371,"ZARMAD":0.505847,"ZARMDL":0.951615,"ZARMGA":244.955968,"ZARMKD":2.957088,"ZARMMK":115.94427,"ZARMNT":198.038697,"ZARMOP":0.446211,"ZARMRU":2.193847,"ZARMUR":2.514741,"ZARMVR":0.850854,"ZARMWK":95.88357,"ZARMXN":1.052244,"ZARMYR":0.235319,"ZARMZN":3.532674,"ZARNAD":0.989769,"ZARNGN":85.521991,"ZARNIO":2.03241,"ZARNOK":0.554208,"ZARNPR":7.658474,"ZARNZD":0.092225,"ZAROMR":0.021237,"ZARPAB":0.055184,"ZARPEN":0.198643,"ZARPGK":0.227335,"ZARPHP":3.16899,"ZARPKR":15.661138,"ZARPLN":0.205376,"ZARPYG":440.464871,"ZARQAR":0.201074,"ZARRON":0.241698,"ZARRSD":5.632938,"ZARRUB":4.328213,"ZARRWF":78.706252,"ZARSAR":0.207257,"ZARSBD":0.460662,"ZARSCR":0.784148,"ZARSDG":33.16712,"ZARSEK":0.530903,"ZARSGD":0.07104,"ZARSHP":0.043404,"ZARSLE":1.239972,"ZARSLL":1158.197221,"ZARSOS":31.565144,"ZARSRD":2.145783,"ZARSTD":1143.200357,"ZARSVC":0.482846,"ZARSYP":718.146724,"ZARSZL":0.990859,"ZARTHB":1.815905,"ZARTJS":0.546301,"ZARTMT":0.193314,"ZARTND":0.162301,"ZARTOP":0.12936,"ZARTRY":2.189337,"ZARTTD":0.375009,"ZARTWD":1.634069,"ZARTZS":144.91848,"ZARUAH":2.304256,"ZARUGX":198.927557,"ZARUSD":0.055232,"ZARUYU":2.257655,"ZARUZS":699.242936,"ZARVES":5.664459,"ZARVND":1443.11365,"ZARVUV":6.63135,"ZARWST":0.146021,"ZARXAF":31.547931,"ZARXAG":0.001519,"ZARXAU":1.6383644e-5,"ZARXCD":0.149268,"ZARXDR":0.039178,"ZARXOF":31.565465,"ZARXPF":5.756602,"ZARYER":13.40494,"ZARZMK":497.158405,"ZARZMW":1.29267,"ZARZWL":17.784829}}');
    if (!$ResponseObj?->success) {
        $APIObj->return($ResponseObj?->error ?? "Failed request");
    }

    $DatabaseObj = new Database();
    $CurrencyObj = new Currency();
    $CurrencyObj->truncate($DatabaseObj);
    foreach ($ResponseObj->quotes as $CurrencyStr => $ConversionRateFlt) {
        $CurrencyObj = new Currency();
        $CurrencyObj->Code = str_replace($ResponseObj->source, "", $CurrencyStr);
        if (!empty($CurrencyObj->loadByCode($DatabaseObj, $CurrencyObj->Code))) {
            $CurrencyObj->createCurrencyFile();
            continue;
        }
        $CurrencyObj->ExchangeRate = $ConversionRateFlt;
        $CurrencyObj->Save($DatabaseObj);
        $CurrencyObj->createCurrencyFile();
    }
    return "success";
}


