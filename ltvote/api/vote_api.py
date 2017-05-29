from collections import defaultdict
from flask import Flask, Response, request
import json

from ltvote import domain


class VoteApp():

    def _request_to_vote(self, data):
        return domain.Vote(
            id=data.get('id', None),
            user_id=data['userId'],
            conference_id=data['conferenceId'],
            speaker_id=data['speakerId'],
            term=data['term'],
            token=data['token']
        )

    def __init__(self, ormapper, vote_repository_factory):
        app = Flask(__name__)
        self.ormapper = ormapper
        self.vote_repository_factory = vote_repository_factory

        @app.route('/votes', methods=['POST'])
        def vote():
            data = request.json()
            vote = self._request_to_vote(data)
            with self.ormapper.create_session() as session:
                vote_repository = self.vote_repository_factory(session)
                vote = vote_repository.update(vote)
            headers = {'content-type': 'application/json'}
            res = vote.__dict__
            return Response(response=json.dumps(res), status=200,
                            headers=headers)

        @app.route('/votes', methods=['GET'])
        def get_results():
            data = request.json()
            with self.ormapper.create_session() as session:
                vote_repository = self.vote_repository_factory(session)
                votes = vote_repository.get_votes(
                    conference_id=data['conferenceId'],
                    term=data['term'],
                    token=data['token']
                )
            headers = {'content-type': 'application/json'}

            res = defaultdict(int)
            for vote in votes:
                res[vote.speaker_id] += 1

            return Response(response=json.dumps(res), status=200,
                            headers=headers)

        self.app = app

    def run(self, host='0.0.0.0', port=80, debug=False):
        self.app.run(host=host, port=port, debug=debug)