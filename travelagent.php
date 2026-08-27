<?php
include("dbconnect.php");
session_start();

$msg = '';

if (!isset($_SESSION['agencyID'])) {
    header("Location: travelagentlogin.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: travelagentlogin.php");
    exit();
}

$userAgencyID = $_SESSION['agencyID'];
$agentInfoQuery = "SELECT ta.agentUsername, a.agencyName 
                   FROM travel_agents ta 
                   LEFT JOIN agency a ON ta.agentEmployer = a.agencyID 
                   WHERE ta.agentID = ?";
$agentInfoStmt = mysqli_prepare($conn, $agentInfoQuery);
mysqli_stmt_bind_param($agentInfoStmt, "i", $_SESSION['agentID']);
mysqli_stmt_execute($agentInfoStmt);
$agentInfoResult = mysqli_stmt_get_result($agentInfoStmt);
$agentInfo = mysqli_fetch_assoc($agentInfoResult);
$agentUsername = $agentInfo['agentUsername'] ?? 'Unknown Agent';
$agencyName = $agentInfo['agencyName'] ?? 'Unknown Agency';
mysqli_stmt_close($agentInfoStmt);

// DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $checkQuery = "SELECT packageID FROM packages WHERE packageID = ? AND agencyID = ?";
    $checkStmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "ii", $id, $userAgencyID);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);
    
    if (mysqli_num_rows($checkResult) > 0) {
        $deleteQuery = "DELETE FROM packages WHERE packageID = ? AND agencyID = ?";
        $stmt = mysqli_prepare($conn, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "ii", $id, $userAgencyID);
        
        if (mysqli_stmt_execute($stmt)) {
            $msg = "<div class='alert alertSuccess'><span class='alertIcon'>✅</span> Package deleted successfully.</div>";
        } else {
            $msg = "<div class='alert alertError'><span class='alertIcon'>❌</span> Error deleting package: " . mysqli_error($conn) . "</div>";
        }
        mysqli_stmt_close($stmt);
    } 
    mysqli_stmt_close($checkStmt);
}

// ADD
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_package'])) {
    $agencyID = $userAgencyID;
    $destinationID = intval($_POST['destinationID']);
    $hotelID = intval($_POST['hotelID']);
    $flightRouteID = intval($_POST['flightRouteID']);
    $packageName = trim($_POST['packageName']);
    $packageDescription = trim($_POST['packageDescription']);
    $packagePrice = floatval($_POST['packagePrice']);
    $packageDuration = trim($_POST['packageDuration']);
    
    if (empty($packageName) || empty($packageDescription) || $packagePrice <= 0) {
        $msg = "<div class='alert alertError'><span class='alertIcon'>❌</span> Please fill all fields with valid data.</div>";
    } else {
        $insertQuery = "INSERT INTO packages (agencyID, destinationID, hotelID, flightRouteID, packageName, packageDescription, packagePrice, packageDuration) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "iiiissds", $agencyID, $destinationID, $hotelID, $flightRouteID, $packageName, $packageDescription, $packagePrice, $packageDuration);
        if (mysqli_stmt_execute($stmt)) {
            $msg = "<div class='alert alertSuccess'><span class='alertIcon'>✅</span> Package added successfully.</div>";
        } else {
            $msg = "<div class='alert alertError'><span class='alertIcon'>❌</span> Error adding package: " . mysqli_error($conn) . "</div>";
        }
        mysqli_stmt_close($stmt);
    }
}

$query = "SELECT 
            p.packageID, p.packageName, p.packageDescription, p.packagePrice, p.packageDuration, p.agencyID, p.destinationID, p.hotelID, p.flightRouteID, 
            a.agencyName,
            d.destinationName, 
            h.hotelName,
            fr.routeDeparturePoint, fr.routeArrivalPoint
          FROM packages p
          LEFT JOIN agency a ON p.agencyID = a.agencyID
          LEFT JOIN destinations d ON p.destinationID = d.destinationID
          LEFT JOIN hotels h ON p.hotelID = h.hotelID
          LEFT JOIN flight_routes fr ON p.flightRouteID = fr.flightRouteID
          WHERE p.agencyID = ?
          ORDER BY p.packageID ASC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $userAgencyID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$agenciesQuery = "SELECT agencyID, agencyName FROM agency ORDER BY agencyName";
$agenciesResult = mysqli_query($conn, $agenciesQuery);

$destinationsQuery = "SELECT destinationID, destinationName FROM destinations ORDER BY destinationName";
$destinationsResult = mysqli_query($conn, $destinationsQuery);

$hotelsQuery = "SELECT hotelID, hotelName FROM hotels ORDER BY hotelName";
$hotelsResult = mysqli_query($conn, $hotelsQuery);

$flightRoutesQuery = "SELECT flightRouteID, routeDeparturePoint, routeArrivalPoint FROM flight_routes ORDER BY routeDeparturePoint";
$flightRoutesResult = mysqli_query($conn, $flightRoutesQuery);

$userAgencyQuery = "SELECT agencyName FROM agency WHERE agencyID = ?";
$userAgencyStmt = mysqli_prepare($conn, $userAgencyQuery);
mysqli_stmt_bind_param($userAgencyStmt, "i", $userAgencyID);
mysqli_stmt_execute($userAgencyStmt);
$userAgencyResult = mysqli_stmt_get_result($userAgencyStmt);
$userAgency = mysqli_fetch_assoc($userAgencyResult);
$userAgencyName = $userAgency['agencyName'] ?? 'Unknown Agency';
mysqli_stmt_close($userAgencyStmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Agent Panel</title>
    <link rel="stylesheet" href="travelagent.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"> 
</head>
<body>
    <div class="header">
        <div class="headerLeft">
            <h1><i class="fa-solid fa-plane"></i> Travel Agent Panel</h1>

            <div class="agentInfoBar">
                <p class="agentName">
                    <i class="fa-solid fa-user"></i> <?= htmlspecialchars($agentUsername) ?>
                    &nbsp;|&nbsp;
                    <i class="fa-solid fa-building"></i> <?= htmlspecialchars($agencyName) ?>
                </p>
            <form method="POST">
                <button type="submit" name="logout" class="logoutBtn">
                    <i class="fa-solid fa-sign-out-alt"></i> Logout
                </button>
            </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="navTabs">
            <button class="navTab active" data-tab="view" onclick="switchTab('view')"><i class="fa-solid fa-eye"></i> View Packages</button>
            <button class="navTab" data-tab="add" onclick="switchTab('add')"><i class="fa-solid fa-plus"></i> Add Package</button>
        </div>

        <?php if (!empty($msg)): ?>
            <?= $msg ?>
        <?php endif; ?>

        <!-- VIEW SECTION -->
        <div class="panel active" id="viewSection">
            <h2><span class="sectionIcon">📋</span> Package Management</h2>
            
            <div class="tableContainer">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Package Name</th>
                            <th>Agency</th>
                            <th>Destination</th>
                            <th>Hotel</th>
                            <th>Flight Route</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)):
                        ?>
                            <tr>
                                <td><?= $row['packageID'] ?></td>
                                <td>
                                    <div class="packageName"><?= htmlspecialchars($row['packageName']) ?></div>
                                    <div class="packageDescription"><?= htmlspecialchars(substr($row['packageDescription'], 0, 50)) ?>...</div>
                                </td>
                                <td><?= htmlspecialchars($row['agencyName'] ?? 'Unknown Agency') ?></td>
                                <td><?= htmlspecialchars($row['destinationName'] ?? 'Unknown Destination') ?></td>
                                <td><?= htmlspecialchars($row['hotelName'] ?? 'Unknown Hotel') ?></td>
                                <td>
                                    <?php if ($row['routeDeparturePoint'] && $row['routeArrivalPoint']): ?>
                                        <?= htmlspecialchars($row['routeDeparturePoint']) ?> → <?= htmlspecialchars($row['routeArrivalPoint']) ?>
                                    <?php else: ?>
                                        Unknown Route
                                    <?php endif; ?>
                                </td>
                                <td><span class="priceDisplay">RM<?= number_format($row['packagePrice'], 2) ?></span></td>
                                <td><?= htmlspecialchars($row['packageDuration']) ?> Days</td>
                                <td>
                                    <form method="POST" style="display: inline;" onsubmit="return confirmDelete('<?= htmlspecialchars($row['packageName']) ?>')">
                                        <input type="hidden" name="delete_id" value="<?= $row['packageID'] ?>">
                                        <button type="submit" class="btn btnDanger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="9" class="noData">
                                    <span class="noDataIcon">📦</span><br>
                                    No packages found. <a href="#" onclick="switchTab('add')">Add packages here</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mobileCards">
                <?php
                if ($result && mysqli_num_rows($result) > 0):
                    mysqli_data_seek($result, 0);
                    while ($row = mysqli_fetch_assoc($result)):
                ?>
                    <div class="packageCard">
                        <div class="packageCardHeader">
                            <div class="packageCardTitle"><?= htmlspecialchars($row['packageName']) ?></div>
                            <div class="packageCardPrice">$<?= number_format($row['packagePrice'], 2) ?></div>
                        </div>
                        <div class="packageCardInfo">
                            <div><strong>Agency:</strong> <?= htmlspecialchars($row['agencyName'] ?? 'Unknown') ?></div>
                            <div><strong>Destination:</strong> <?= htmlspecialchars($row['destinationName'] ?? 'Unknown') ?></div>
                            <div><strong>Hotel:</strong> <?= htmlspecialchars($row['hotelName'] ?? 'Unknown') ?></div>
                            <div><strong>Flight Route:</strong> 
                                <?php if ($row['routeDeparturePoint'] && $row['routeArrivalPoint']): ?>
                                    <?= htmlspecialchars($row['routeDeparturePoint']) ?> → <?= htmlspecialchars($row['routeArrivalPoint']) ?>
                                <?php else: ?>
                                    Unknown Route
                                <?php endif; ?>
                            </div>
                        </div>
                        <p class="packageCardDescription"><?= htmlspecialchars(substr($row['packageDescription'], 0, 100)) ?>...</p>
                        <p class="packageCardDuration"><span style="font-size: 16px;">📅</span> Duration: <?= htmlspecialchars($row['packageDuration']) ?> Days</p>
                        <form method="POST" onsubmit="return confirmDelete('<?= htmlspecialchars($row['packageName']) ?>')">
                            <input type="hidden" name="delete_id" value="<?= $row['packageID'] ?>">
                            <button type="submit" class="btn btnDanger">Delete</button>
                        </form>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <div class="noData">
                        <span class="noDataIcon">📦</span><br>
                        No packages found. <a href="#" onclick="switchTab('add')">Add your first package</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ADD SECTION -->
        <div class="panel" id="addSection">
            <h2>Add New Travel Package</h2>
            
            <form method="POST" onsubmit="return validateForm()">
                <input type="hidden" name="add_package" value="1">
                
                <div class="formGrid">
                    <div class="formGroup">
                        <label><span class="labelIcon">🏢</span> Agency</label>
                        <input type="hidden" name="agencyID" value="<?= $userAgencyID ?>">
                        <input type="text" value="<?= htmlspecialchars($userAgencyName) ?>" disabled>
                    </div>
                    
                    <div class="formGroup">
                        <label><span class="labelIcon">📍</span> Destination</label>
                        <select name="destinationID" required class="addSelect">
                            <option value="">Select Destination</option>
                            <?php while ($destination = mysqli_fetch_assoc($destinationsResult)): ?>
                                <option value="<?= $destination['destinationID'] ?>"><?= htmlspecialchars($destination['destinationName']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="formGroup">
                        <label><span class="labelIcon">🏨</span> Hotel</label>
                        <select name="hotelID" required class="addSelect">
                            <option value="">Select Hotel</option>
                            <?php while ($hotel = mysqli_fetch_assoc($hotelsResult)): ?>
                                <option value="<?= $hotel['hotelID'] ?>"><?= htmlspecialchars($hotel['hotelName']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="formGroup">
                        <label><i class="fa-solid fa-plane-departure"></i> Flight Route</label>
                        <select name="flightRouteID" required class="addSelect">
                            <option value="">Select Flight Route</option>
                            <?php while ($route = mysqli_fetch_assoc($flightRoutesResult)): ?>
                                <option value="<?= $route['flightRouteID'] ?>">
                                    <?= htmlspecialchars($route['routeDeparturePoint']) ?> → <?= htmlspecialchars($route['routeArrivalPoint']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                <div class="formGroup">
                    <label><span class="labelIcon">📅</span> Package Duration</label>
                    <input type="number" name="packageDuration" step="1" min="0" required placeholder="Total amount of Days">
                </div>
                    
                    <div class="formGroup">
                        <label><span class="labelIcon">💰</span> Package Price</label>
                        <input type="number" name="packagePrice" step="1 "min="0" required placeholder="RM0.00">
                    </div>
                </div>
                
                <div class="formGroup" style="margin-bottom: 10px;">
                    <label><span class="labelIcon">📦</span> Package Name</label>
                    <input type="text" name="packageName" required maxlength="100" placeholder="Enter package name">
                </div>

                <div class="formGroup">
                    <label><span class="labelIcon">📝</span> Package Description</label>
                    <textarea name="packageDescription" rows="4" style="resize: none;" required placeholder="Enter package features or highlights"></textarea>
                </div>           

                <button type="submit" class="btn btnPrimary" style="margin-top: 13px;"><i class="fa-solid fa-plus"></i> Add Package</button>
            </form>
        </div>
    </div>
<script src="travelagent.js"></script>
</body>
</html>
