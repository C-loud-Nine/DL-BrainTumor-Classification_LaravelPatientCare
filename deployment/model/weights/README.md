# Model weights

Place the three trained **SavedModel directories** here (not `.h5` files):

```text
presys/    tumour classifier - primary        (150x150x1)
1sys/      tumour classifier - second opinion (150x150x1)
102mod/    MRI vs non-MRI validator           (128x128x3)
```

Each directory contains `saved_model.pb` plus a `variables/` folder.

Stage them automatically from your local training output:

```powershell
powershell -ExecutionPolicy Bypass -File deployment\collect_weights.ps1
```

These files are not tracked in git (see `GITIGNORE_ADDITIONS.txt`). For an
archived release, include them so the artefact is self-contained.
