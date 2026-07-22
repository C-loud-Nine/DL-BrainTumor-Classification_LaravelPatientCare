@echo off
REM ============================================================
REM  Launch the three brain-tumor FastAPI model servers.
REM  Uses the "tensorflow" conda env (TF 2.10 + keras + opencv).
REM
REM    8001  presys  (forceful tumor classification, no MRI gate)
REM    8002  presys  (primary: MRI detect + tumor classify)
REM    8003  1sys    (second model for dual comparison)
REM
REM  Run this, then start Laravel with:  php artisan serve
REM ============================================================
set PY=C:\Users\Shafi.09\anaconda3\envs\tensorflow\python.exe
set BRAINT=C:\Users\Shafi.09\AnocondaProjects\BrainT\Final training\models
set HERE=%~dp0

start "model-8001-presys" cmd /k "set PORT=8001&& set TUMOR_MODEL=%BRAINT%\presys&& "%PY%" "%HERE%model_server.py""
start "model-8002-presys" cmd /k "set PORT=8002&& set TUMOR_MODEL=%BRAINT%\presys&& "%PY%" "%HERE%model_server.py""
start "model-8003-1sys"   cmd /k "set PORT=8003&& set TUMOR_MODEL=%BRAINT%\1sys&& "%PY%" "%HERE%model_server.py""

echo Launched 3 model servers on 8001/8002/8003. Give them ~20s to load.
