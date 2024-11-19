#!/bin/bash

# Start the PHP server in the background
php -S 0.0.0.0:8081 -t public &

php public/worker/processQueue.php & 

# Start the main process in the background and save its PID
./main &
main_pid=$!


# Wait for the main process to exit and check its status
wait $main_pid
main_exit_status=$?

# If main process exited with a non-zero status, print an error message
if [ $main_exit_status -ne 0 ]; then
    echo "Error: main process exited with status $main_exit_status"
else
    echo "Main process exited successfully"
fi

# Keep the container running with the PHP server active
wait
