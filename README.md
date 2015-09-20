# synchcopy
Copy files from source directory to current directory and skipping any that already exist.

Can handle large directory sizes.

Current logic:
- If same filename exists -> skip
- If zero size file -> skip

