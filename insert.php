<?php

 
$sup = ["public\factures\ddYlbr6H2X4Y6mL3rxw4zLqc0iU1b4a66Nq7sHd9b9s.pdf",
        "public\factures\h85jL2dE-4-Cu8HzYi4J7BsPKAnXFEHHA-mCUgDSyCI.pdf",
        "public\factures\IJ7WHKbg1AyLVBP6iFJODMclWUbEGfILKXEDubJ4sSo.pdf",
        "public\factures\JMqONeRBbZVyGA4LWOw2qlKL4_RqTW3l0gGLW6iIOnE.pdf",
        "public\factures\MAFmpLBNxx7cXvjta0ofWZP4GrJBQd4aoB0V1x1qcfE.pdf",
        "public\factures\oMuVziE7RLAoMHI3zIhclRKJuGrkfK_7B3dg4YIfJZA.pdf",
        "public\factures\Pb61_N6RBWEBnBOwjtiZpBcfmPrqmLB9lgoeu1wA4O4.pdf",
        "public\factures\XWZEH0ZMsEWjAr_JBYi4i-cwCvTXUwAXvcbt7xHlJjI.pdf",
        "public\factures\zqqgncxBq7GD0H3xi_8OCiXte8eS4TXSXRkkUwpLuX4.pdf"
];

    foreach($sup as $del){
        $var = "/www/hedee/" . $del;
        exec("rm $var");
    }
?>