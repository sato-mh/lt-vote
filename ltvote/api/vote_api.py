from collections import defaultdict
from flask import Flask, Response, request
from flask_cors import CORS
import json

from ltvote import domain


class VoteApp():

    def _request_to_vote(self, data):
        return domain.Vote(
            id_=data.get('id', None),
            user_id=data['userId'],
            conference_id=data['conferenceId'],
            speaker_id=data['speakerId'],
            term=data['term'],
            token=data['token']
        )

    def __init__(self, ormapper, vote_repository_factory):
        app = Flask(__name__)
        CORS(app)
        self.ormapper = ormapper
        self.vote_repository_factory = vote_repository_factory
        # origin='116.118.226.94'

        @app.route('/votes', methods=['POST'])
        def vote():
            data = request.json
            vote = self._request_to_vote(data)
            headers = {'content-type': 'application/json'}
            try:
                with self.ormapper.create_session() as session:
                    vote_repository = self.vote_repository_factory.create(
                        session)
                    vote = vote_repository.save(vote)
            except Exception:
                res = {'error': 'Already saved.'}
                return Response(response=json.dumps(res), status=400,
                                headers=headers)
            res = vote.__dict__
            return Response(response=json.dumps(res), status=200,
                            headers=headers)

        @app.route('/votes', methods=['GET'])
        def get_results():
            data = request.args
            with self.ormapper.create_session() as session:
                vote_repository = self.vote_repository_factory.create(session)
                votes = vote_repository.get_votes(
                    conference_id=data['conferenceId'],
                    term=data['term']
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
