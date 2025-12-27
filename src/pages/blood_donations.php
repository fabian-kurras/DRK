<?php
/**
 * Blutspendetermine Seite
 */
$title = 'Blutspendetermine - DRK Oberberg SW';
$db = $GLOBALS['db'];

try {
    require_once SRC_PATH . '/classes/BloodDonation.php';
    $donationObj = new BloodDonation($db);
    $upcomingDonations = $donationObj->getUpcoming(10);
} catch (Exception $e) {
    error_log('BloodDonation Error: ' . $e->getMessage());
    $upcomingDonations = [];
}
?>

<section class="mb-12">
    <h1 class="text-4xl font-bold mb-6">Blutspendetermine</h1>
    
    <!-- Info Box -->
    <div class="bg-red-50 border-2 border-red-200 rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-bold text-red-700 mb-3">🩸 Ihre Blutspende rettet Leben</h2>
        <p class="text-gray-700 mb-4">
            Das Deutsche Rote Kreuz ist ständig auf Blutspenden angewiesen, um Patienten in Krankenhäusern und bei Unfällen versorgen zu können.
            Die regelmäßige Blutspende ist eine wichtige Möglichkeit, konkret Leben zu retten.
        </p>
        <p class="text-gray-600">
            <strong>Voraussetzungen:</strong> Sie müssen mindestens 18 Jahre alt sein, sich gesund fühlen und über 50 kg wiegen.
        </p>
    </div>
    
    <!-- Calendar View -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-8">
        <h2 class="text-2xl font-bold mb-6">📅 Termine in Ihrer Nähe</h2>
        
        <?php 
        $showError = false;
        if (empty($upcomingDonations)): 
            // Überprüfe ob Tabelle existiert
            try {
                $check = $db->query("SHOW TABLES LIKE 'blood_donations'");
                $tableExists = $check && $check->rowCount() > 0;
                $showError = !$tableExists;
            } catch (Exception $e) {
                $showError = true;
            }
        ?>
            <div class="bg-gray-100 rounded-lg p-6 text-center">
                <?php if ($showError): ?>
                    <p class="text-gray-700 mb-4">⚠️ Blutspendetermine sind noch nicht eingerichtet.</p>
                    <p class="text-gray-500 text-sm">Der Administrator kann diese unter dem Admin-Panel aktivieren.</p>
                <?php else: ?>
                    <p class="text-gray-600 mb-4">Zur Zeit sind keine Blutspendetermine geplant.</p>
                    <p class="text-gray-500 text-sm">Bitte kontaktieren Sie uns für weitere Informationen.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($upcomingDonations as $donation): 
                    $donationDateTime = strtotime($donation['donation_date'] . ' ' . $donation['donation_time']);
                    $isUpcoming = $donationDateTime > time();
                    $daysUntil = ceil(($donationDateTime - time()) / (60 * 60 * 24));
                ?>
                <div class="bg-gradient-to-br from-red-50 to-white border-2 border-red-200 rounded-lg p-6 hover:shadow-lg transition">
                    <!-- Date Header -->
                    <div class="bg-red-700 text-white rounded p-4 mb-4 text-center">
                        <div class="text-3xl font-bold">
                            <?php echo date('d', $donationDateTime); ?>
                        </div>
                        <div class="text-sm font-semibold">
                            <?php 
                            $month = date('m', $donationDateTime);
                            $year = date('Y', $donationDateTime);
                            $months = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
                            echo $months[(int)$month - 1] . ' ' . $year;
                            ?>
                        </div>
                    </div>
                    
                    <!-- Time -->
                    <div class="mb-4">
                        <div class="text-gray-600 text-sm font-bold">Uhrzeit</div>
                        <div class="text-lg font-bold text-gray-800">
                            ⏰ <?php echo substr($donation['donation_time'], 0, 5); ?> Uhr
                        </div>
                    </div>
                    
                    <!-- Location -->
                    <div class="mb-4">
                        <div class="text-gray-600 text-sm font-bold">Ort</div>
                        <div class="text-gray-800">
                            📍 <?php echo h($donation['location']); ?>
                        </div>
                    </div>
                    
                    <!-- Time until -->
                    <?php if ($isUpcoming && $daysUntil >= 0): ?>
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 p-3 rounded">
                        <p class="text-yellow-800 text-sm font-bold">
                            <?php 
                            if ($daysUntil == 0) {
                                echo '🔴 Heute!';
                            } elseif ($daysUntil == 1) {
                                echo '🟡 Morgen!';
                            } else {
                                echo '🟢 in ' . $daysUntil . ' Tagen';
                            }
                            ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- FAQ Section -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold mb-6">❓ Häufig gestellte Fragen</h2>
        
        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-bold text-red-700 mb-2">Wie lange dauert eine Blutspende?</h3>
                <p class="text-gray-700">
                    Die gesamte Spende dauert etwa 10-15 Minuten. Mit Anmeldung und Nachbetreuung sollten Sie etwa 30-45 Minuten einplanen.
                </p>
            </div>
            
            <div>
                <h3 class="text-lg font-bold text-red-700 mb-2">Wie oft kann ich Blut spenden?</h3>
                <p class="text-gray-700">
                    Männer können etwa alle 8 Wochen, Frauen alle 12 Wochen Blut spenden. Insgesamt sind bis zu 4-6 Spenden pro Jahr möglich.
                </p>
            </div>
            
            <div>
                <h3 class="text-lg font-bold text-red-700 mb-2">Ist Blutspenden kostenlos?</h3>
                <p class="text-gray-700">
                    Ja, Blutspenden sind kostenlos und völlig unverbindlich. Nach der Spende erhalten Sie eine kleine Aufwandsentschädigung.
                </p>
            </div>
            
            <div>
                <h3 class="text-lg font-bold text-red-700 mb-2">Welche Blutgruppen werden benötigt?</h3>
                <p class="text-gray-700">
                    Alle Blutgruppen sind wichtig! Besonders benötigt werden O- und O+ Blutgruppen, da diese universell einsetzbar sind.
                </p>
            </div>
        </div>
    </div>
</section>
