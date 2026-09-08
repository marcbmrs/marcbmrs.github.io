# Skill: Design Renan Post Approval Flow

## Purpose

Design or review the flow after a budget is approved: contract preparation, work creation, client page access, and updates.

## Procedure

1. Identify the approved budget and related records: contract minuta, work record, client access token/code, and updates.
2. Make the next action depend on current state:
   - no minuta: prepare contract;
   - minuta saved and no work: create work;
   - work exists: update work or open client page.
3. Avoid duplicate works/contracts for the same budget.
4. Keep contract language marked as operational draft unless legally reviewed.
5. Keep client links and private photos protected.
6. Validate affected PHP/JS and review mobile visibility of the next step.

## Output

State map, next-action recommendation, data dependency, UI change, and production risk if applicable.
