#language: en

Business Need: I want to search commits by phrase

    Scenario: I send all required parameters and get response status code 200
        When I add "Content-Type" header equal to "application/json"
        And I add "Accept" header equal to "application/json"
        When I send a "POST" request to "/api/search" with body:
        """
            {
                "repositoryName": "https://github.com/cocoders/git-history-searcher.git",
                "phrase": "jobs"
            }
        """
        Then the response status code should be 200
        And the JSON should be equal to:
        """
            [
                {
                    "hash": "698f8dc751d955a4bdfe1e41cac9bd53ab81b431",
                    "author": {
                        "name": "Piotr",
                        "email": "some@email.com"
                    },
                    "comment": "Share cache between jobs",
                    "committedAt": "2020-09-19T14:43:11+02:00"
                }
            ]
        """

    Scenario: I send incomplete parameters and get response 400
        When I add "Content-Type" header equal to "application/json"
        And I add "Accept" header equal to "application/json"
        When I send a "POST" request to "/api/search" with body:
        """
            {

            }
        """
        Then the response status code should be 400
        And the JSON should be equal to:
        """
            {
                "repositoryName": ["This value should not be blank."],
                "phrase": ["This value should not be blank."]
            }
        """
