<!-- Begin Search -->
<form class="form-inline my-2 my-lg-0" action="{{ route('search.show') }}">



    <?php if (isset($_GET['q'])) { ?>
    <input id="txtGoogleSearch" name="q" class="form-control mr-sm-2" type="text"
        placeholder="{{  config('constant.SEARCH_INPUT_TEXT') }}" aria-haspopup="true" aria-controls="prova-menu"
        value="<?php echo $_GET['q']  ?>">
    <?php } else {?>

    <input id="txtGoogleSearch" name="q" class="form-control mr-sm-2" type="text"
        placeholder="{{  config('constant.SEARCH_INPUT_TEXT') }}" aria-haspopup="true" aria-controls="prova-menu">

    <?php } ?>

    <div class="col-12 col-md-2">
        <button class="btn follow" type="submit">
            Search
        </button>
    </div>
</form>
<!-- End Search -->