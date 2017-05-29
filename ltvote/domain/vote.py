class Vote():

    def __init__(self, user_id, conference_id, speaker_id, term, token,
                 id_=None, created_at=None, updated_at=None):
        self.id = id_
        self.user_id = user_id
        self.conference_id = conference_id
        self.speaker_id = speaker_id
        self.term = term
        self.token = token
        self.created_at = None
        self.update_at = None
