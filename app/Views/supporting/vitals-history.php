<?php

declare(strict_types=1);

$title = 'Vitals History';
$active = 'vitals-history';

require __DIR__ . '/header.php';
?>

<div class="supporting-vh-header">
  <div class="supporting-vh-header__title-group">
    <h1 class="supporting-vh-header__title">Vitals recorded today</h1>
  </div>
  <div class="supporting-vh-header__search">
    <span class="supporting-vh-header__search-icon"><?= icon('search', 14) ?></span>
    <input type="text" id="vitals-search-input" class="supporting-vh-header__search-input" placeholder="Filter by patient…">
  </div>
</div>

<div class="supporting-vh-card">
  <div class="table-responsive">
    <table class="data-table supporting-vh-table" id="vitals-history-table">
      <thead>
        <tr>
          <th>TIME</th>
          <th>PATIENT</th>
          <th>BP</th>
          <th>TEMP</th>
          <th>PULSE</th>
          <th>SPO₂</th>
          <th>WEIGHT</th>
          <th>HEIGHT</th>
          <th>BMI</th>
          <th>RECORDED BY</th>
        </tr>
      </thead>
      <tbody>
        <tr class="supporting-vh-row">
          <td class="supporting-vh-cell--time"><strong>09:26</strong></td>
          <td>
            <div class="supporting-vh-patient">
              <strong class="supporting-vh-patient__name">Nimsith Wickrama</strong>
              <span class="supporting-vh-patient__id">PT-0912</span>
            </div>
          </td>
          <td>128/84</td>
          <td>37.9°</td>
          <td>82</td>
          <td>98%</td>
          <td>78.4 kg</td>
          <td>171 cm</td>
          <td><strong class="supporting-vh-bmi">26.8</strong></td>
          <td>
            <div class="supporting-vh-by">
              <span>Omindu G.</span>
              <span class="supporting-vh-by__ready">· → Ready</span>
            </div>
          </td>
        </tr>
        <tr class="supporting-vh-row">
          <td class="supporting-vh-cell--time"><strong>09:18</strong></td>
          <td>
            <div class="supporting-vh-patient">
              <strong class="supporting-vh-patient__name">K.A. Inuka Asith</strong>
              <span class="supporting-vh-patient__id">PT-1088</span>
            </div>
          </td>
          <td>118/76</td>
          <td>36.6°</td>
          <td>71</td>
          <td>99%</td>
          <td>58.0 kg</td>
          <td>157 cm</td>
          <td><strong class="supporting-vh-bmi">23.5</strong></td>
          <td>
            <div class="supporting-vh-by">
              <span>Omindu G.</span>
              <span class="supporting-vh-by__ready">· → Ready</span>
            </div>
          </td>
        </tr>
        <tr class="supporting-vh-row">
          <td class="supporting-vh-cell--time"><strong>09:05</strong></td>
          <td>
            <div class="supporting-vh-patient">
              <strong class="supporting-vh-patient__name">Sandanu Dulmeth</strong>
              <span class="supporting-vh-patient__id">PT-0230</span>
            </div>
          </td>
          <td>142/92</td>
          <td>36.7°</td>
          <td>88</td>
          <td>97%</td>
          <td>66.3 kg</td>
          <td>160 cm</td>
          <td><strong class="supporting-vh-bmi">25.9</strong></td>
          <td>
            <div class="supporting-vh-by">
              <span>Mithun M.</span>
              <span class="supporting-vh-by__ready">· → Ready</span>
            </div>
          </td>
        </tr>
        <tr class="supporting-vh-row">
          <td class="supporting-vh-cell--time"><strong>08:52</strong></td>
          <td>
            <div class="supporting-vh-patient">
              <strong class="supporting-vh-patient__name">Nimsith Wickrama</strong>
              <span class="supporting-vh-patient__id">PT-0844</span>
            </div>
          </td>
          <td>121/79</td>
          <td>36.5°</td>
          <td>69</td>
          <td>99%</td>
          <td>54.1 kg</td>
          <td>152 cm</td>
          <td><strong class="supporting-vh-bmi">23.4</strong></td>
          <td>
            <div class="supporting-vh-by">
              <span>Mithun M.</span>
              <span class="supporting-vh-by__ready">· → Ready</span>
            </div>
          </td>
        </tr>
        <tr class="supporting-vh-row">
          <td class="supporting-vh-cell--time"><strong>08:40</strong></td>
          <td>
            <div class="supporting-vh-patient">
              <strong class="supporting-vh-patient__name">M. L. Omindu Gunathilaka</strong>
              <span class="supporting-vh-patient__id">PT-0644</span>
            </div>
          </td>
          <td>131/85</td>
          <td>36.9°</td>
          <td>77</td>
          <td>98%</td>
          <td>80.2 kg</td>
          <td>175 cm</td>
          <td><strong class="supporting-vh-bmi">26.2</strong></td>
          <td>
            <div class="supporting-vh-by">
              <span>Omindu G.</span>
              <span class="supporting-vh-by__ready">· → Ready</span>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

</div>

<script src="/assets/js/supporting/vitals-history.js" defer></script>
<?php require __DIR__ . '/footer.php'; ?>