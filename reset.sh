#!/bin/bash

# Reset the poll_votes table
sqlite3 poll.db "DELETE FROM poll_votes"
